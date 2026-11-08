@extends('layouts.app')

@section('title', 'Reservation #' . $reservation->id . ' - PickleSlot')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('reservations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to my bookings
        </a>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-3.5 py-1.5 text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Pass
            </button>

            @if ($reservation->canBeCancelled())
                <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                    @csrf
                    <button type="submit" class="rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 px-3.5 py-1.5 text-xs font-bold transition">
                        Cancel Reservation
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Booking Pass / Receipt Card -->
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-md">
        <!-- Receipt Top Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-8 text-white">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 backdrop-blur-md px-3 py-1 text-xs font-bold uppercase tracking-wider text-emerald-100">
                        {{ $reservation->status }}
                    </span>
                    <h1 class="mt-2 text-3xl font-black">Booking Pass #{{ $reservation->id }}</h1>
                    <p class="mt-1 text-xs text-emerald-100">Created on {{ $reservation->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>

                <div class="text-right">
                    <span class="text-xs uppercase tracking-wider text-emerald-200">Amount Due</span>
                    <p class="text-3xl font-extrabold">${{ number_format($reservation->total_price, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Receipt Body -->
        <div class="p-8 space-y-8">
            <!-- Court & Schedule Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Court</p>
                    <p class="mt-1 font-bold text-slate-900 text-base">{{ $reservation->court->name }}</p>
                    <p class="text-xs text-slate-500 capitalize">{{ $reservation->court->type }} • {{ $reservation->court->surface }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Date</p>
                    <p class="mt-1 font-bold text-slate-900 text-base">{{ $reservation->date->format('M d, Y') }}</p>
                    <p class="text-xs text-slate-500">{{ $reservation->date->format('l') }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Time Slot</p>
                    <p class="mt-1 font-bold text-slate-900 text-base">{{ substr((string) $reservation->start_time, 0, 5) }} – {{ substr((string) $reservation->end_time, 0, 5) }}</p>
                    <p class="text-xs text-slate-500">1-Hour Session</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Player</p>
                    <p class="mt-1 font-bold text-slate-900 text-base">{{ $reservation->user->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ $reservation->user->email }}</p>
                </div>
            </div>

            <!-- Equipment Rentals Table -->
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-3">Rented Gear & Add-ons</h3>

                @if ($reservation->reservationEquipments->isEmpty())
                    <div class="rounded-xl bg-slate-50 p-4 text-xs text-slate-500 border border-slate-100">
                        No equipment rented with this reservation. Players bring their own paddles & balls.
                    </div>
                @else
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                                <tr>
                                    <th class="py-3 px-4 text-left">Item Description</th>
                                    <th class="py-3 px-4 text-center">Category</th>
                                    <th class="py-3 px-4 text-center">Qty</th>
                                    <th class="py-3 px-4 text-right">Unit Price</th>
                                    <th class="py-3 px-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($reservation->reservationEquipments as $item)
                                    <tr>
                                        <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $item->equipment->name }}</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="rounded-md px-2 py-0.5 text-xs font-semibold capitalize {{ $item->equipment->category === 'paddle' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700' }}">
                                                {{ $item->equipment->category }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-slate-900">{{ $item->quantity }}</td>
                                        <td class="py-3.5 px-4 text-right text-slate-600">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="py-3.5 px-4 text-right font-bold text-slate-900">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Price Breakdown Calculation -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-6 space-y-2.5 text-sm">
                <div class="flex items-center justify-between text-slate-600">
                    <span>Court Fee (1 hour @ ${{ number_format($reservation->court_rate, 2) }}/hr):</span>
                    <span class="font-bold text-slate-900">${{ number_format($reservation->court_total, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span>Equipment Rental Subtotal:</span>
                    <span class="font-bold text-slate-900">${{ number_format($reservation->equipment_total, 2) }}</span>
                </div>
                <div class="border-t border-slate-200 pt-3 flex items-baseline justify-between">
                    <span class="text-base font-extrabold text-slate-900">Total Price Due:</span>
                    <span class="text-2xl font-black text-emerald-600">${{ number_format($reservation->total_price, 2) }}</span>
                </div>
            </div>

            @if ($reservation->notes)
                <div class="rounded-xl border border-slate-200 p-4 text-xs">
                    <p class="font-bold text-slate-700 uppercase tracking-wider mb-1">Player Notes</p>
                    <p class="text-slate-600">{{ $reservation->notes }}</p>
                </div>
            @endif

            <!-- Check-in instructions -->
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-5 text-xs text-emerald-900 flex items-start gap-3">
                <svg class="h-5 w-5 shrink-0 text-emerald-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="font-bold text-emerald-950">Facility Check-in Notice</h4>
                    <p class="mt-0.5">Please present this booking reference number <strong>#{{ $reservation->id }}</strong> at the front reception 10 minutes prior to match time to receive your court assignment, rented paddles, and fresh balls.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

