<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Court;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\ReservationEquipment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display a listing of user reservations.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $status = $request->query('status');

        $query = Reservation::with(['court', 'reservationEquipments.equipment'])
            ->where('user_id', $user->id);

        if ($status === 'upcoming') {
            $query->where('status', 'confirmed')
                ->where('date', '>=', now()->toDateString());
        } elseif ($status === 'completed') {
            $query->where(function ($q) {
                $q->where('status', 'completed')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'confirmed')
                            ->where('date', '<', now()->toDateString());
                    });
            });
        } elseif ($status === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $reservations = $query->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        $upcomingCount = Reservation::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where('date', '>=', now()->toDateString())
            ->count();

        $totalCount = Reservation::where('user_id', $user->id)->count();

        return view('reservations.index', compact('reservations', 'status', 'upcomingCount', 'totalCount'));
    }

    /**
     * Show the form for creating a new reservation.
     */
    public function create(Request $request): View
    {
        $courts = Court::active()->orderBy('name')->get();
        $paddles = Equipment::active()->paddles()->get();
        $balls = Equipment::active()->balls()->get();

        $selectedCourtId = (int) $request->query('court_id', $courts->first()?->id ?? 0);
        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        // Precompute booked slots for default view
        $bookedSlots = [];
        if ($selectedCourtId) {
            $bookedSlots = Reservation::query()
                ->activeStatus()
                ->where('court_id', $selectedCourtId)
                ->whereDate('date', $selectedDate)
                ->get(['start_time', 'end_time'])
                ->map(fn ($r) => [
                    'start' => substr((string) $r->start_time, 0, 5),
                    'end' => substr((string) $r->end_time, 0, 5),
                ])
                ->all();
        }

        return view('reservations.create', compact('courts', 'paddles', 'balls', 'selectedCourtId', 'selectedDate', 'bookedSlots'));
    }

    /**
     * API check for booked slots on a court and date.
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $courtId = (int) $request->query('court_id');
        $date = $request->query('date');

        if (! $courtId || ! $date) {
            return response()->json(['slots' => []]);
        }

        $reservations = Reservation::query()
            ->activeStatus()
            ->where('court_id', $courtId)
            ->whereDate('date', $date)
            ->get(['start_time', 'end_time']);

        $slots = $reservations->map(fn ($r) => [
            'start' => substr((string) $r->start_time, 0, 5),
            'end' => substr((string) $r->end_time, 0, 5),
        ]);

        return response()->json(['slots' => $slots]);
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = Auth::user();

        $court = Court::findOrFail($validated['court_id']);

        $startDateTime = Carbon::parse($validated['date'].' '.$validated['start_time']);
        $endDateTime = Carbon::parse($validated['date'].' '.$validated['end_time']);

        if ($startDateTime->isPast()) {
            throw ValidationException::withMessages([
                'start_time' => 'Cannot reserve a time slot in the past.',
            ]);
        }

        $durationMinutes = $startDateTime->diffInMinutes($endDateTime);
        if ($durationMinutes <= 0) {
            throw ValidationException::withMessages([
                'end_time' => 'End time must be after start time.',
            ]);
        }

        $durationHours = $durationMinutes / 60;
        $courtTotal = round((float) $court->hourly_rate * $durationHours, 2);

        $startTimeNormalized = strlen($validated['start_time']) === 5 ? $validated['start_time'].':00' : $validated['start_time'];
        $endTimeNormalized = strlen($validated['end_time']) === 5 ? $validated['end_time'].':00' : $validated['end_time'];

        $selectedEquipment = array_filter(
            $validated['equipment'] ?? [],
            fn ($qty) => is_numeric($qty) && (int) $qty > 0
        );

        $reservation = DB::transaction(function () use (
            $validated,
            $user,
            $court,
            $courtTotal,
            $selectedEquipment,
            $startTimeNormalized,
            $endTimeNormalized
        ) {
            // 1. Conflict Check: check if court is already booked for an overlapping time
            $hasConflict = Reservation::query()
                ->activeStatus()
                ->where('court_id', $court->id)
                ->whereDate('date', $validated['date'])
                ->where(function ($query) use ($startTimeNormalized, $endTimeNormalized) {
                    $query->where('start_time', '<', $endTimeNormalized)
                        ->where('end_time', '>', $startTimeNormalized);
                })
                ->lockForUpdate()
                ->exists();

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'start_time' => 'This court is already reserved during the requested time slot. Please choose another time or court.',
                ]);
            }

            // 2. Equipment Inventory Check
            $equipmentTotal = 0.00;
            $equipmentToAttach = [];

            if (! empty($selectedEquipment)) {
                $equipmentIds = array_keys($selectedEquipment);
                $equipmentModels = Equipment::whereIn('id', $equipmentIds)->lockForUpdate()->get()->keyBy('id');

                foreach ($selectedEquipment as $eqId => $requestedQty) {
                    $equipment = $equipmentModels->get($eqId);
                    if (! $equipment) {
                        continue;
                    }

                    $requestedQty = (int) $requestedQty;

                    // Calculate total quantity currently booked for this equipment during the overlapping window
                    $alreadyRentedQty = DB::table('reservation_equipment')
                        ->join('reservations', 'reservation_equipment.reservation_id', '=', 'reservations.id')
                        ->whereDate('reservations.date', $validated['date'])
                        ->where('reservations.status', '!=', 'cancelled')
                        ->where('reservations.start_time', '<', $endTimeNormalized)
                        ->where('reservations.end_time', '>', $startTimeNormalized)
                        ->where('reservation_equipment.equipment_id', $equipment->id)
                        ->sum('reservation_equipment.quantity');

                    $availableStock = $equipment->stock - $alreadyRentedQty;

                    if ($requestedQty > $availableStock) {
                        throw ValidationException::withMessages([
                            'equipment' => "Insufficient stock for {$equipment->name}. Only {$availableStock} available for this time window.",
                        ]);
                    }

                    $lineTotal = round((float) $equipment->price * $requestedQty, 2);
                    $equipmentTotal += $lineTotal;

                    $equipmentToAttach[] = [
                        'equipment_id' => $equipment->id,
                        'quantity' => $requestedQty,
                        'unit_price' => $equipment->price,
                        'total_price' => $lineTotal,
                    ];
                }
            }

            $totalPrice = $courtTotal + $equipmentTotal;

            // 3. Create Reservation
            $reservation = Reservation::create([
                'user_id' => $user->id,
                'court_id' => $court->id,
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'court_rate' => $court->hourly_rate,
                'court_total' => $courtTotal,
                'equipment_total' => $equipmentTotal,
                'total_price' => $totalPrice,
                'status' => 'confirmed',
                'notes' => $validated['notes'] ?? null,
            ]);

            // 4. Attach Equipment records
            foreach ($equipmentToAttach as $item) {
                ReservationEquipment::create([
                    'reservation_id' => $reservation->id,
                    'equipment_id' => $item['equipment_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }

            return $reservation;
        });

        return redirect()->route('reservations.show', $reservation)
            ->with('status', 'Reservation confirmed successfully! Get ready to play.');
    }

    /**
     * Display the specified reservation.
     */
    public function show(Reservation $reservation): View
    {
        $user = Auth::user();
        if ($reservation->user_id !== $user->id && ! $user->is_admin) {
            abort(403, 'Unauthorized access to this reservation.');
        }

        $reservation->load(['court', 'reservationEquipments.equipment', 'user']);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Cancel an upcoming reservation.
     */
    public function cancel(Reservation $reservation): RedirectResponse
    {
        $user = Auth::user();
        if ($reservation->user_id !== $user->id && ! $user->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        if (! $reservation->canBeCancelled()) {
            return back()->with('error', 'Only future, non-cancelled reservations can be cancelled.');
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('status', 'Reservation has been cancelled successfully.');
    }
}
