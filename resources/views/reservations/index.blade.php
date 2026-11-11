@extends('layouts.app')

@section('title', 'My Court Bookings - PickleSlot')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">My Court Bookings</h1>
                <p class="mt-2 text-base text-slate-600">View upcoming reservations, review your rented paddles and balls,
                    or manage your schedule.</p>
            </div>
            <a href="{{ route('reservations.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition">
                + New Reservation
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 mb-6">
            <a href="{{ route('reservations.index') }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ empty($status) ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                All ({{ $totalCount }})
            </a>
            <a href="{{ route('reservations.index', ['status' => 'upcoming']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $status === 'upcoming' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Upcoming Matches ({{ $upcomingCount }})
            </a>
            <a href="{{ route('reservations.index', ['status' => 'completed']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Completed
            </a>
            <a href="{{ route('reservations.index', ['status' => 'cancelled']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $status === 'cancelled' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Cancelled
            </a>
        </div>

        @if ($reservations->isEmpty())
            <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-white p-12 text-center">
                <span
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </span>
                <h3 class="text-lg font-bold text-slate-900">No Reservations Found</h3>
                <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">You haven't reserved any court yet. Pick a time and get
                    playing!</p>
                <div class="mt-6">
                    <a href="{{ route('reservations.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition">
                        Book Your First Court
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($reservations as $reservation)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <!-- Court & Date Info -->
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="rounded-lg px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider {{ $reservation->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($reservation->status === 'completed' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-red-50 text-red-700 border border-red-200') }}">
                                        {{ $reservation->status }}
                                    </span>
                                    <span class="text-xs text-slate-400">#{{ $reservation->id }}</span>
                                    <span class="text-xs text-slate-500">Booked
                                        {{ $reservation->created_at->diffForHumans() }}</span>
                                </div>

                                <h3 class="text-xl font-bold text-slate-900">
                                    {{ $reservation->displayCourts()->pluck('name')->join(', ') }}</h3>

                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-600">
                                    <span class="flex items-center gap-1 font-semibold text-slate-800">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $reservation->date->format('l, F j, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1 font-semibold text-slate-800">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ substr((string) $reservation->start_time, 0, 5) }} –
                                        {{ substr((string) $reservation->end_time, 0, 5) }}
                                    </span>
                                    <span class="rounded bg-slate-100 px-2 py-0.5 text-slate-700 capitalize">
                                        {{ $reservation->displayCourts()->pluck('type')->unique()->join(', ') }}
                                    </span>
                                </div>

                                <!-- Equipment Rented Pill Tags -->
                                @if ($reservation->reservationEquipments->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap items-center gap-2 pt-2">
                                        <span class="text-xs font-semibold text-slate-500">Gear Rented:</span>
                                        @foreach ($reservation->reservationEquipments as $item)
                                            <span
                                                class="inline-flex items-center gap-1 rounded-lg {{ $item->equipment->category === 'paddle' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-amber-50 text-amber-800 border-amber-200' }} border px-2 py-0.5 text-xs font-medium">
                                                <strong>{{ $item->quantity }}x</strong> {{ $item->equipment->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Price & Action Buttons -->
                            <div
                                class="flex flex-row md:flex-col items-center md:items-end justify-between gap-3 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                                <div class="text-right">
                                    <span class="text-xs text-slate-400">Total Price</span>
                                    <p class="text-2xl font-black text-slate-900">₱{{ number_format($reservation->total_price, 2) }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('reservations.show', $reservation) }}"
                                        class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                                        View Receipt
                                    </a>

                                    @if ($reservation->canBeCancelled())
                                        <form method="POST" action="{{ route('reservations.cancel', $reservation) }}"
                                            onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 text-xs font-bold transition">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $reservations->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection