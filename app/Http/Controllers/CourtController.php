<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourtController extends Controller
{
    /**
     * Display a listing of courts.
     */
    public function index(Request $request): View
    {
        $type = $request->query('type');

        $query = Court::query()->active();

        if ($type && in_array($type, ['indoor', 'outdoor', 'covered'], true)) {
            $query->where('type', $type);
        }

        $courts = $query->orderBy('name')->get();

        return view('courts.index', compact('courts', 'type'));
    }

    /**
     * Display the specified court.
     */
    public function show(Court $court): View
    {
        abort_unless($court->is_active, 404);

        $upcomingReservations = $court->reservations()
            ->activeStatus()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('courts.show', compact('court', 'upcomingReservations'));
    }
}
