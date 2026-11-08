<?php

use App\Http\Controllers\Admin\AdminCourtController;
use App\Http\Controllers\Admin\AdminEquipmentController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReservationController;
use App\Models\Court;
use App\Models\Equipment;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    $courts = Court::active()->orderBy('name')->get();
    $equipment = Equipment::active()->orderBy('name')->get();

    return view('home', compact('courts', 'equipment'));
})->name('home');

// Public Court & Equipment Browsing
Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');
Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
Route::get('/api/availability', [ReservationController::class, 'checkAvailability'])->name('api.availability');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Customer Reservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Admin Management Area
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Courts Management
        Route::resource('courts', AdminCourtController::class);
        Route::patch('courts/{court}/toggle', [AdminCourtController::class, 'toggleStatus'])->name('courts.toggle');

        // Equipment Management
        Route::resource('equipment', AdminEquipmentController::class);

        // Reservations Management
        Route::get('reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::patch('reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.status');
    });
});
