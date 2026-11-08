<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminReservationController extends Controller
{
    /**
     * Display a listing of all reservations for facility administration.
     */
    public function index(Request $request): View
    {
        $courtId = $request->query('court_id');
        $status = $request->query('status');
        $date = $request->query('date');

        $query = Reservation::with(['court', 'courts', 'user', 'reservationEquipments.equipment'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc');

        if ($courtId) {
            $query->forCourts([(int) $courtId]);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($date) {
            $query->where('date', $date);
        }

        $reservations = $query->paginate(15)->withQueryString();
        $courts = Court::orderBy('name')->get();

        return view('admin.reservations.index', compact('reservations', 'courts', 'courtId', 'status', 'date'));
    }

    /**
     * Update the status of a reservation.
     */
    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:confirmed,completed,cancelled'],
        ]);

        DB::transaction(function () use ($reservation, $validated) {
            $lockedReservation = Reservation::query()
                ->lockForUpdate()
                ->findOrFail($reservation->id);

            $lockedReservation->transitionStatus($validated['status']);
        });

        return back()->with('status', "Reservation #{$reservation->id} status updated to {$validated['status']}.");
    }
}
