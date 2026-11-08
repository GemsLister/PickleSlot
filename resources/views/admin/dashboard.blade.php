@extends('layouts.app')

@section('title', 'Admin Dashboard - PickleSlot')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <!-- Admin Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-6 mb-8">
        <div>
            <div class="flex items-center gap-2">
                <span class="rounded-lg bg-amber-100 text-amber-800 px-2 py-0.5 text-xs font-bold uppercase tracking-wider">Facility Admin</span>
                <span class="text-xs text-slate-400">Control Center</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl mt-1">PickleSlot Overview</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.courts.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                Manage Courts
            </a>
            <a href="{{ route('admin.equipment.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                Manage Gear & Stock
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="rounded-xl bg-slate-900 text-white px-4 py-2 text-xs font-bold hover:bg-slate-800 transition">
                All Bookings
            </a>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Bookings</p>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-3xl font-black text-slate-900">{{ $totalReservations }}</p>
                <span class="text-xs text-emerald-600 font-semibold">{{ $confirmedReservations }} confirmed</span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Revenue</p>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-3xl font-black text-emerald-600">${{ number_format($totalRevenue, 2) }}</p>
                <span class="text-xs text-slate-400">courts & gear</span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Courts</p>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-3xl font-black text-slate-900">{{ $activeCourtsCount }}</p>
                <span class="text-xs text-slate-500 font-medium">available for play</span>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Rental Gear Inventory</p>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-3xl font-black text-slate-900">{{ $paddleCount + $ballCount }}</p>
                <span class="text-xs text-slate-500">{{ $paddleCount }} paddles • {{ $ballCount }} balls</span>
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Today's Court Schedule</h2>
                <p class="text-xs text-slate-500">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <span class="rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 text-xs font-bold">
                {{ count($todayReservations) }} Matches Today
            </span>
        </div>

        @if ($todayReservations->isEmpty())
            <p class="text-sm text-slate-500 py-6 text-center">No reservations scheduled for today yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                        <tr>
                            <th class="py-3 px-4 text-left">Time Slot</th>
                            <th class="py-3 px-4 text-left">Court</th>
                            <th class="py-3 px-4 text-left">Player</th>
                            <th class="py-3 px-4 text-right">Total</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($todayReservations as $res)
                            <tr>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ substr((string) $res->start_time, 0, 5) }} – {{ substr((string) $res->end_time, 0, 5) }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $res->displayCourts()->pluck('name')->join(', ') }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $res->user->name }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-slate-900">${{ number_format($res->total_price, 2) }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('reservations.show', $res) }}" class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Recent Bookings -->
    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">Recent Bookings Across Facility</h2>
            <a href="{{ route('admin.reservations.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                View all bookings &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                    <tr>
                        <th class="py-3 px-4 text-left">Booking #</th>
                        <th class="py-3 px-4 text-left">Court</th>
                        <th class="py-3 px-4 text-left">Player</th>
                        <th class="py-3 px-4 text-left">Date & Time</th>
                        <th class="py-3 px-4 text-left">Rented Gear</th>
                        <th class="py-3 px-4 text-right">Total Price</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($recentReservations as $res)
                        <tr>
                            <td class="py-3.5 px-4 font-bold text-slate-900">#{{ $res->id }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $res->displayCourts()->pluck('name')->join(', ') }}</td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $res->user->name }}</td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $res->date->format('M d, Y') }} ({{ substr((string) $res->start_time, 0, 5) }}-{{ substr((string) $res->end_time, 0, 5) }})
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500">
                                @forelse ($res->reservationEquipments as $item)
                                    <div>{{ $item->quantity }}x {{ $item->equipment->name }}</div>
                                @empty
                                    <span class="text-slate-400">None</span>
                                @endforelse
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-slate-900">${{ number_format($res->total_price, 2) }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="rounded-md px-2 py-0.5 text-xs font-bold uppercase {{ $res->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($res->status === 'completed' ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $res->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
