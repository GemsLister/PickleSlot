@extends('layouts.app')

@section('title', $court->name . ' - Court Details')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('courts.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to all courts
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Court Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <span
                            class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold {{ $court->type === 'indoor' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($court->type === 'covered' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }} uppercase tracking-wider">
                            {{ $court->type }} Court
                        </span>
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Active & Available
                        </span>
                    </div>

                    <h1 class="text-3xl font-extrabold text-slate-900">{{ $court->name }}</h1>
                    <p class="mt-4 text-slate-600 leading-relaxed text-base">{{ $court->description }}</p>

                    <!-- Court Specs -->
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-slate-100 pt-6">
                        <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Surface</p>
                            <p class="mt-1 font-bold text-slate-800">{{ $court->surface }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Type</p>
                            <p class="mt-1 font-bold text-slate-800 capitalize">{{ $court->type }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Hourly Rate</p>
                            <p class="mt-1 font-bold text-emerald-600 text-lg">${{ number_format($court->hourly_rate, 2) }}
                                <span class="text-xs text-slate-500 font-normal">/ hour</span></p>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Schedule on This Court -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Upcoming Schedule on this Court</h2>
                    @if ($upcomingReservations->isEmpty())
                        <p class="text-sm text-slate-500 py-4">No upcoming reservations yet. All time slots are currently open!
                        </p>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach ($upcomingReservations as $reservation)
                                <div class="py-3 flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-slate-800">{{ $reservation->date->format('M d, Y') }}</span>
                                        <span class="text-xs text-slate-500">({{ $reservation->date->format('l') }})</span>
                                    </div>
                                    <span
                                        class="rounded-lg bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-1 text-xs font-semibold">
                                        Booked: {{ substr((string) $reservation->start_time, 0, 5) }} -
                                        {{ substr((string) $reservation->end_time, 0, 5) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Sidebar Card -->
            <div class="space-y-6">
                <div
                    class="rounded-2xl border border-emerald-200 bg-gradient-to-b from-emerald-50/50 to-white p-6 shadow-sm sticky top-24">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Reserve this court</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="text-3xl font-extrabold text-slate-900">${{ number_format($court->hourly_rate, 2) }}</span>
                        <span class="text-sm text-slate-500">per 1-hour session</span>
                    </div>

                    <div class="mt-6 space-y-3 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Professional nets & LED court lighting
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Add paddle and ball rentals in booking
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Free cancellation up until session start
                        </div>
                    </div>

                    <div class="mt-8">
                        <x-buttons.primary-button href="{{ route('reservations.create', ['court_id' => $court->id]) }}"
                            class="w-full">
                            Select Date & Reserve Now
                        </x-buttons.primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection