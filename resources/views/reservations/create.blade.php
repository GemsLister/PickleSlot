@extends('layouts.app')

@section('title', 'Book a Court & Rent Equipment - PickleSlot')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="border-b border-slate-200 pb-6 mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Reserve a Pickleball Court</h1>
            <p class="mt-2 text-base text-slate-600">Select one or more courts for the same time slot, then add premium
                paddles or official balls for rental.</p>
        </div>

        <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Court, Date, Time & Equipment -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Step 1: Select Court -->
                    <x-sections.booking-page.step-1 :courts="$courts" :selectedCourtIds="$selectedCourtIds" />


                    <!-- Step 2: Date & Time Slot Selection -->
                    <x-sections.booking-page.step-2 :selectedDate="$selectedDate" />

                    <!-- Step 3: Equipment Rental Add-ons (Paddles & Balls) -->
                    <x-sections.booking-page.step-3 :paddles="$paddles" :balls="$balls" />

                    <!-- Special Notes -->
                    <x-sections.booking-page.special-notes />
                </div>

                <!-- Right Column: Sticky Live Booking Summary -->
                <x-sections.booking-page.reservation-summary />
            </div>
            <script type="application/json" id="equipment-data">@json($paddles->merge($balls)->keyBy('id'))</script>
            <script type="application/json" id="booked-slots-data">@json($bookedSlots)</script>
        </form>
    </div>
@endsection