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

        $query = Reservation::with(['court', 'courts', 'reservationEquipments.equipment'])
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

        $selectedCourtIds = old(
            'court_ids',
            [$request->query('court_id', $courts->first()?->id)]
        );
        $selectedCourtIds = array_values(array_intersect(
            $courts->modelKeys(),
            array_map('intval', (array) $selectedCourtIds)
        ));
        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        $bookedSlots = [];
        if ($selectedCourtIds !== []) {
            $bookedSlots = Reservation::query()
                ->activeStatus()
                ->forCourts($selectedCourtIds)
                ->whereDate('date', $selectedDate)
                ->get(['start_time', 'end_time'])
                ->map(fn ($r) => [
                    'start' => substr((string) $r->start_time, 0, 5),
                    'end' => substr((string) $r->end_time, 0, 5),
                ])
                ->all();
        }

        return view('reservations.create', compact('courts', 'paddles', 'balls', 'selectedCourtIds', 'selectedDate', 'bookedSlots'));
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
            ->forCourts([$courtId])
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

        $courtIds = array_map('intval', $validated['court_ids']);

        $startDateTime = Carbon::parse($validated['date'].' '.$validated['start_time']);
        $endDateTime = Carbon::parse($validated['date'].' '.$validated['end_time']);

        if ($startDateTime->isPast()) {
            throw ValidationException::withMessages([
                'start_time' => 'Cannot reserve a time slot in the past.',
            ]);
        }

        $durationMinutes = (int) $startDateTime->diffInMinutes($endDateTime);
        if (
            $durationMinutes <= 0
            || $durationMinutes % 60 !== 0
            || $durationMinutes !== (int) $validated['duration_hours'] * 60
            || $startDateTime->minute !== 0
            || $endDateTime->minute !== 0
        ) {
            throw ValidationException::withMessages([
                'end_time' => 'Choose whole-hour times that match the selected reservation duration.',
            ]);
        }

        if ($startDateTime->format('H:i') < '07:00' || $endDateTime->format('H:i') > '22:00') {
            throw ValidationException::withMessages([
                'start_time' => 'Reservations must be within facility operating hours (07:00 AM–10:00 PM).',
            ]);
        }

        $durationHours = $durationMinutes / 60;
        $startTimeNormalized = strlen($validated['start_time']) === 5 ? $validated['start_time'].':00' : $validated['start_time'];
        $endTimeNormalized = strlen($validated['end_time']) === 5 ? $validated['end_time'].':00' : $validated['end_time'];

        $selectedEquipment = array_filter(
            $validated['equipment'] ?? [],
            fn ($qty) => is_numeric($qty) && (int) $qty > 0
        );

        $reservation = DB::transaction(function () use (
            $validated,
            $user,
            $courtIds,
            $durationHours,
            $selectedEquipment,
            $startTimeNormalized,
            $endTimeNormalized
        ) {
            $courts = Court::query()
                ->active()
                ->whereKey($courtIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($courts->count() !== count($courtIds)) {
                throw ValidationException::withMessages([
                    'court_ids' => 'Choose one or more active courts to reserve.',
                ]);
            }

            $court = $courts->firstOrFail();
            $courtRate = round((float) $courts->sum(fn (Court $court): float => (float) $court->hourly_rate), 2);
            $courtTotal = round($courtRate * $durationHours, 2);

            $hasConflict = Reservation::query()
                ->activeStatus()
                ->forCourts($courtIds)
                ->whereDate('date', $validated['date'])
                ->where(function ($query) use ($startTimeNormalized, $endTimeNormalized) {
                    $query->where('start_time', '<', $endTimeNormalized)
                        ->where('end_time', '>', $startTimeNormalized);
                })
                ->lockForUpdate()
                ->exists();

            if ($hasConflict) {
                throw ValidationException::withMessages([
                    'start_time' => 'At least one selected court is already reserved during the requested time slot. Please choose another time or court.',
                ]);
            }

            // 2. Equipment Inventory Check
            $equipmentTotal = 0.00;
            $equipmentToAttach = [];

            if (! empty($selectedEquipment)) {
                $equipmentIds = array_keys($selectedEquipment);
                $equipmentModels = Equipment::whereIn('id', $equipmentIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($selectedEquipment as $eqId => $requestedQty) {
                    $equipment = $equipmentModels->get($eqId);
                    if (! $equipment) {
                        continue;
                    }

                    $requestedQty = (int) $requestedQty;

                    $availableStock = $equipment->stock;

                    if ($requestedQty > $availableStock) {
                        throw ValidationException::withMessages([
                            'equipment' => "Insufficient stock for {$equipment->name}. Only {$availableStock} available.",
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
                'court_rate' => $courtRate,
                'court_total' => $courtTotal,
                'equipment_total' => $equipmentTotal,
                'total_price' => $totalPrice,
                'status' => 'confirmed',
                'notes' => $validated['notes'] ?? null,
            ]);

            $reservation->courts()->attach($courts->modelKeys());

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

            $reservation->reserveEquipmentStock();

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

        $reservation->load(['court', 'courts', 'reservationEquipments.equipment', 'user']);

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

        $wasCancelled = DB::transaction(function () use ($reservation) {
            $reservation = Reservation::query()
                ->lockForUpdate()
                ->findOrFail($reservation->id);

            if (! $reservation->canBeCancelled()) {
                return false;
            }

            $reservation->transitionStatus('cancelled');

            return true;
        });

        if (! $wasCancelled) {
            return back()->with('error', 'Only future, non-cancelled reservations can be cancelled.');
        }

        return back()->with('status', 'Reservation has been cancelled successfully.');
    }
}
