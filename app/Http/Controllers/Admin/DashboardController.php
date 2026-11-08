<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin overview dashboard.
     */
    public function index(): View
    {
        $totalReservations = Reservation::count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();
        $totalRevenue = Reservation::where('status', '!=', 'cancelled')->sum('total_price');
        $activeCourtsCount = Court::where('is_active', true)->count();
        $totalUsersCount = User::where('is_admin', false)->count();

        $paddleCount = Equipment::paddles()->sum('stock');
        $ballCount = Equipment::balls()->sum('stock');

        $todayReservations = Reservation::with(['court', 'courts', 'user'])
            ->where('date', now()->toDateString())
            ->where('status', 'confirmed')
            ->orderBy('start_time')
            ->get();

        $recentReservations = Reservation::with(['court', 'courts', 'user', 'reservationEquipments.equipment'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalReservations',
            'confirmedReservations',
            'totalRevenue',
            'activeCourtsCount',
            'totalUsersCount',
            'paddleCount',
            'ballCount',
            'todayReservations',
            'recentReservations'
        ));
    }
}
