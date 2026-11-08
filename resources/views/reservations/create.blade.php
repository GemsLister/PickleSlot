@extends('layouts.app')

@section('title', 'Book a Court & Rent Equipment - PickleSlot')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="border-b border-slate-200 pb-6 mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Reserve a Pickleball Court</h1>
        <p class="mt-2 text-base text-slate-600">Select one or more courts for the same time slot, then add premium paddles or official balls for rental.</p>
    </div>

    <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Court, Date, Time & Equipment -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Step 1: Select Court -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">1</span>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Choose Courts</h2>
                            <p class="text-xs text-slate-500">Select every court you want to reserve for the same session.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="courtList">
                        @foreach ($courts as $court)
                            <label class="court-card relative flex flex-col justify-between rounded-xl border-2 p-4 cursor-pointer transition-all {{ in_array($court->id, $selectedCourtIds, true) ? 'border-emerald-600 bg-emerald-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300' }}">
                                <input type="checkbox" name="court_ids[]" value="{{ $court->id }}" data-rate="{{ $court->hourly_rate }}" data-name="{{ $court->name }}" class="sr-only" {{ in_array($court->id, $selectedCourtIds, true) ? 'checked' : '' }} onchange="onCourtChange()">
                                <div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="rounded-md px-2 py-0.5 text-[11px] font-bold uppercase {{ $court->type === 'indoor' ? 'bg-blue-100 text-blue-800' : ($court->type === 'covered' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ $court->type }}
                                        </span>
                                        <span class="font-extrabold text-slate-900 text-base">${{ number_format($court->hourly_rate, 2) }}<span class="text-xs font-normal text-slate-500">/hr</span></span>
                                    </div>
                                    <h3 class="mt-2 text-base font-bold text-slate-900 leading-snug">{{ $court->name }}</h3>
                                    <p class="mt-1 text-xs text-slate-500">{{ $court->surface }}</p>
                                </div>
                                <div class="mt-3 flex items-center gap-1.5 text-xs text-emerald-700 font-semibold selection-indicator {{ in_array($court->id, $selectedCourtIds, true) ? 'opacity-100' : 'opacity-0' }}">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                    Selected
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @if ($errors->has('court_ids') || $errors->has('court_ids.*'))
                        <p class="mt-3 text-sm font-medium text-red-600">
                            {{ $errors->first('court_ids') ?: $errors->first('court_ids.*') }}
                        </p>
                    @endif
                </div>

                <!-- Step 2: Date & Time Slot Selection -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">2</span>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Select Date & Time Slot</h2>
                            <p class="text-xs text-slate-500">Facility operating hours: 07:00 AM – 10:00 PM</p>
                        </div>
                    </div>

                    <!-- Date Picker with Quick Jump Buttons -->
                    <div class="space-y-3">
                        <label for="reservationDate" class="block text-sm font-semibold text-slate-700">Reservation Date</label>
                        <div class="flex flex-wrap items-center gap-3">
                            <input type="date" id="reservationDate" name="date" min="{{ now()->format('Y-m-d') }}" value="{{ $selectedDate }}"
                                class="rounded-xl border border-slate-300 px-3.5 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                onchange="onDateChange()">

                            <button type="button" onclick="setQuickDate(0)" class="rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                                Today
                            </button>
                            <button type="button" onclick="setQuickDate(1)" class="rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                                Tomorrow
                            </button>
                            <button type="button" onclick="setQuickDate(2)" class="rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                                +2 Days
                            </button>
                        </div>
                    </div>

                    <!-- Visual Slot Grid -->
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-semibold text-slate-700">Choose Start Time</label>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="flex items-center gap-1.5 text-slate-600">
                                    <span class="h-3 w-3 rounded bg-white border border-slate-300"></span> Available
                                </span>
                                <span class="flex items-center gap-1.5 text-emerald-700 font-medium">
                                    <span class="h-3 w-3 rounded bg-emerald-600"></span> Selected
                                </span>
                                <span class="flex items-center gap-1.5 text-slate-400">
                                    <span class="h-3 w-3 rounded bg-slate-100 border border-slate-200"></span> Unavailable
                                </span>
                            </div>
                        </div>

                        <!-- Slots Grid -->
                        <div id="slotsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                            <!-- Populated dynamically via JS -->
                        </div>
                        <p id="availabilityError" class="mt-3 hidden text-sm font-medium text-red-600" role="alert"></p>

                        <div class="mt-5 max-w-xs">
                            <label for="durationHours" class="block text-sm font-semibold text-slate-700">Reservation Duration</label>
                            <select id="durationHours" name="duration_hours" required onchange="onDurationChange()"
                                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                @for ($duration = 1; $duration <= 15; $duration++)
                                    <option value="{{ $duration }}" {{ (int) old('duration_hours', 1) === $duration ? 'selected' : '' }}>
                                        {{ $duration }} {{ $duration === 1 ? 'hour' : 'hours' }}
                                    </option>
                                @endfor
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Choose consecutive hours. Availability and price update automatically.</p>
                        </div>

                        <!-- Hidden time inputs sent to backend -->
                        <input type="hidden" id="startTimeInput" name="start_time" value="{{ old('start_time', '09:00') }}" required>
                        <input type="hidden" id="endTimeInput" name="end_time" value="{{ old('end_time', '10:00') }}" required>

                        <div class="mt-4 rounded-xl bg-slate-50 p-3.5 flex items-center justify-between text-xs text-slate-600 border border-slate-100">
                            <span>Selected Session:</span>
                            <span id="selectedSlotSummary" class="font-bold text-slate-900 text-sm">
                                {{ old('start_time', '09:00') }} - {{ old('end_time', '10:00') }} ({{ old('duration_hours', 1) }} {{ (int) old('duration_hours', 1) === 1 ? 'hour' : 'hours' }})
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Equipment Rental Add-ons (Paddles & Balls) -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">3</span>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Rental Equipment Add-ons</h2>
                            <p class="text-xs text-slate-500">Need paddles or fresh balls? Add them below and pick up at the court desk.</p>
                        </div>
                    </div>

                    <!-- Paddles Group -->
                    <div class="mt-6">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Paddle Rentals</h3>
                        <div class="space-y-3">
                            @foreach ($paddles as $paddle)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-slate-300 transition">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-slate-900 text-sm">{{ $paddle->name }}</h4>
                                            <span class="rounded bg-purple-50 text-purple-700 px-1.5 py-0.5 text-[10px] font-semibold uppercase">Paddle</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $paddle->description }}</p>
                                        <p class="text-xs font-semibold text-emerald-600 mt-1">${{ number_format($paddle->price, 2) }} / session <span class="text-slate-400 font-normal">({{ $paddle->stock }} in stock)</span></p>
                                    </div>

                                    <!-- Stepper Counter -->
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-id="{{ $paddle->id }}" onclick="decrementEquipment(this)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">-</button>
                                        <input type="number" id="qty_{{ $paddle->id }}" name="equipment[{{ $paddle->id }}]" value="{{ old('equipment.'.$paddle->id, 0) }}" min="0" max="{{ $paddle->stock }}" readonly
                                            class="w-12 text-center rounded-lg border border-slate-200 py-1 text-sm font-bold text-slate-900 focus:outline-none bg-slate-50">
                                        <button type="button" data-id="{{ $paddle->id }}" data-stock="{{ $paddle->stock }}" onclick="incrementEquipment(this)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Balls Group -->
                    <div class="mt-8">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ball Rentals & Sets</h3>
                        <div class="space-y-3">
                            @foreach ($balls as $ball)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-slate-300 transition">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-slate-900 text-sm">{{ $ball->name }}</h4>
                                            <span class="rounded bg-amber-50 text-amber-800 px-1.5 py-0.5 text-[10px] font-semibold uppercase">Balls</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $ball->description }}</p>
                                        <p class="text-xs font-semibold text-emerald-600 mt-1">${{ number_format($ball->price, 2) }} / session <span class="text-slate-400 font-normal">({{ $ball->stock }} available)</span></p>
                                    </div>

                                    <!-- Stepper Counter -->
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-id="{{ $ball->id }}" onclick="decrementEquipment(this)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">-</button>
                                        <input type="number" id="qty_{{ $ball->id }}" name="equipment[{{ $ball->id }}]" value="{{ old('equipment.'.$ball->id, 0) }}" min="0" max="{{ $ball->stock }}" readonly
                                            class="w-12 text-center rounded-lg border border-slate-200 py-1 text-sm font-bold text-slate-900 focus:outline-none bg-slate-50">
                                        <button type="button" data-id="{{ $ball->id }}" data-stock="{{ $ball->stock }}" onclick="incrementEquipment(this)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Special Notes -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <label for="notes" class="block text-sm font-semibold text-slate-700">Special Requests / Player Notes <span class="text-xs font-normal text-slate-400">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="2" placeholder="e.g., Playing doubles practice, left-handed paddle preferred, etc."
                        class="mt-2 block w-full rounded-xl border border-slate-300 p-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Sticky Live Booking Summary -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Reservation Summary</h2>

                    <div class="mt-4 space-y-3.5 text-sm">
                        <!-- Court Selection -->
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Courts:</span>
                            <span id="summaryCourtName" class="font-bold text-slate-900 text-right">-</span>
                        </div>

                        <!-- Date -->
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Date:</span>
                            <span id="summaryDate" class="font-semibold text-slate-800 text-right">-</span>
                        </div>

                        <!-- Time Slot -->
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Time:</span>
                            <span id="summaryTime" class="font-semibold text-slate-800 text-right">-</span>
                        </div>

                        <!-- Duration -->
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Duration:</span>
                            <span id="summaryDuration" class="font-semibold text-slate-800 text-right">1 hour</span>
                        </div>

                        <!-- Court Subtotal -->
                        <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                            <span class="text-slate-600">Court Subtotal:</span>
                            <span id="summaryCourtFee" class="font-bold text-slate-900">$0.00</span>
                        </div>

                        <!-- Gear Subtotal -->
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Gear Rental:</span>
                            <span id="summaryGearFee" class="font-bold text-slate-900">$0.00</span>
                        </div>

                        <!-- Rented Gear Items breakdown -->
                        <div id="summaryGearList" class="text-xs text-slate-500 space-y-1 pl-2 border-l-2 border-slate-200 hidden">
                            <!-- Injected by JS -->
                        </div>

                        <!-- Grand Total -->
                        <div class="border-t-2 border-slate-100 pt-4 flex items-baseline justify-between">
                            <div>
                                <span class="text-base font-extrabold text-slate-900">Total Due</span>
                                <p class="text-[11px] text-slate-400">Payable at check-in</p>
                            </div>
                            <span id="summaryTotalFee" class="text-2xl font-black text-emerald-600">$0.00</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <button type="submit" id="submitBtn" class="flex w-full justify-center items-center gap-2 rounded-xl bg-emerald-600 px-4 py-3.5 text-base font-bold text-white shadow-md hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Confirm Reservation
                        </button>
                        <p class="mt-2 text-center text-[11px] text-slate-400">
                            Instant confirmation. Free cancellation before match start.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script type="application/json" id="equipment-data">{!! json_encode($paddles->merge($balls)->keyBy('id')) !!}</script>
<script type="application/json" id="booked-slots-data">{!! json_encode($bookedSlots) !!}</script>
<script>
const equipmentData = JSON.parse(document.getElementById('equipment-data').textContent);
let bookedSlots = JSON.parse(document.getElementById('booked-slots-data').textContent);

// Available start times from 07:00 to 21:00; sessions end by 22:00.
const hours = [
    { start: '07:00', end: '08:00', label: '7:00 - 8:00 AM' },
    { start: '08:00', end: '09:00', label: '8:00 - 9:00 AM' },
    { start: '09:00', end: '10:00', label: '9:00 - 10:00 AM' },
    { start: '10:00', end: '11:00', label: '10:00 - 11:00 AM' },
    { start: '11:00', end: '12:00', label: '11:00 - 12:00 PM' },
    { start: '12:00', end: '13:00', label: '12:00 - 1:00 PM' },
    { start: '13:00', end: '14:00', label: '1:00 - 2:00 PM' },
    { start: '14:00', end: '15:00', label: '2:00 - 3:00 PM' },
    { start: '15:00', end: '16:00', label: '3:00 - 4:00 PM' },
    { start: '16:00', end: '17:00', label: '4:00 - 5:00 PM' },
    { start: '17:00', end: '18:00', label: '5:00 - 6:00 PM' },
    { start: '18:00', end: '19:00', label: '6:00 - 7:00 PM' },
    { start: '19:00', end: '20:00', label: '7:00 - 8:00 PM' },
    { start: '20:00', end: '21:00', label: '8:00 - 9:00 PM' },
    { start: '21:00', end: '22:00', label: '9:00 - 10:00 PM' },
];

const durationInput = document.getElementById('durationHours');
let selectedDurationHours = Number(durationInput.value) || 1;
let selectedSlot = {
    start: document.getElementById('startTimeInput').value || '09:00',
    end: document.getElementById('endTimeInput').value || '10:00'
};

function getEndTime(start, duration) {
    const [startHour, startMinute] = start.split(':').map(Number);
    const endMinutes = startHour * 60 + startMinute + duration * 60;
    const endHour = Math.floor(endMinutes / 60);
    const endMinute = endMinutes % 60;

    return `${String(endHour).padStart(2, '0')}:${String(endMinute).padStart(2, '0')}`;
}

function isSlotAvailable(start, duration = selectedDurationHours) {
    const end = getEndTime(start, duration);

    if (end > '22:00' || getSelectedCourtInputs().length === 0) {
        return false;
    }

    return !bookedSlots.some(slot => start < slot.end && end > slot.start);
}

function setSelectedSlot(start) {
    selectedSlot = start && isSlotAvailable(start)
        ? { start, end: getEndTime(start, selectedDurationHours) }
        : { start: '', end: '' };

    document.getElementById('startTimeInput').value = selectedSlot.start;
    document.getElementById('endTimeInput').value = selectedSlot.end;
    document.getElementById('selectedSlotSummary').innerText = selectedSlot.start
        ? `${selectedSlot.start} - ${selectedSlot.end} (${selectedDurationHours} ${selectedDurationHours === 1 ? 'hour' : 'hours'})`
        : `No available ${selectedDurationHours}-hour sessions`;
}

function ensureSelectedSlotIsAvailable() {
    if (getSelectedCourtInputs().length === 0) {
        setSelectedSlot('');
        return;
    }

    const availableStart = hours.some(slot => slot.start === selectedSlot.start && isSlotAvailable(slot.start))
        ? selectedSlot.start
        : hours.find(slot => isSlotAvailable(slot.start))?.start;

    setSelectedSlot(availableStart || '');
}

function renderSlots() {
    const container = document.getElementById('slotsGrid');
    container.innerHTML = '';

    hours.forEach(slot => {
        const isAvailable = isSlotAvailable(slot.start);
        const isSelected = slot.start === selectedSlot.start;
        const end = getEndTime(slot.start, selectedDurationHours);

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.disabled = !isAvailable;

        let baseClasses = 'flex flex-col items-center justify-center rounded-xl p-2.5 text-xs font-semibold transition border ';
        if (!isAvailable) {
            btn.className = baseClasses + 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed opacity-60';
            btn.innerHTML = `<span class="line-through">${slot.start}</span><span class="text-[10px] text-red-500 font-bold uppercase mt-0.5">Unavailable</span>`;
        } else if (isSelected) {
            btn.className = baseClasses + 'border-emerald-600 bg-emerald-600 text-white shadow-sm shadow-emerald-600/30 ring-2 ring-emerald-600/20';
            btn.innerHTML = `<span>${slot.start} - ${end}</span><span class="text-[10px] text-emerald-100 mt-0.5 font-normal">Selected</span>`;
        } else {
            btn.className = baseClasses + 'border-slate-200 bg-white text-slate-800 hover:border-emerald-500 hover:bg-emerald-50/40 cursor-pointer';
            btn.innerHTML = `<span>${slot.start} - ${end}</span><span class="text-[10px] text-slate-400 mt-0.5">Available</span>`;
        }

        if (isAvailable) {
            btn.onclick = () => selectSlot(slot.start);
        }

        container.appendChild(btn);
    });
}

function selectSlot(start) {
    setSelectedSlot(start);
    renderSlots();
    updateSummary();
}

function onDurationChange() {
    selectedDurationHours = Number(durationInput.value) || 1;
    ensureSelectedSlotIsAvailable();
    renderSlots();
    updateSummary();
}

function onCourtChange() {
    document.querySelectorAll('.court-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        const indicator = card.querySelector('.selection-indicator');
        if (checkbox.checked) {
            card.classList.add('border-emerald-600', 'bg-emerald-50/50', 'shadow-sm');
            card.classList.remove('border-slate-200');
            indicator.classList.remove('opacity-0');
        } else {
            card.classList.remove('border-emerald-600', 'bg-emerald-50/50', 'shadow-sm');
            card.classList.add('border-slate-200');
            indicator.classList.add('opacity-0');
        }
    });

    fetchAvailability();
    updateSummary();
}

function onDateChange() {
    fetchAvailability();
    updateSummary();
}

function setQuickDate(daysFromToday) {
    const d = new Date();
    d.setDate(d.getDate() + daysFromToday);
    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    document.getElementById('reservationDate').value = `${yyyy}-${mm}-${dd}`;
    onDateChange();
}

async function fetchAvailability() {
    const selectedCourtInputs = getSelectedCourtInputs();
    const availabilityError = document.getElementById('availabilityError');
    availabilityError.classList.add('hidden');

    if (selectedCourtInputs.length === 0) {
        bookedSlots = [];
        ensureSelectedSlotIsAvailable();
        renderSlots();
        updateSummary();
        return;
    }

    const date = document.getElementById('reservationDate').value;

    try {
        const responses = await Promise.all(selectedCourtInputs.map(input =>
            fetch(`/api/availability?court_id=${encodeURIComponent(input.value)}&date=${encodeURIComponent(date)}`)
        ));
        if (responses.some(response => !response.ok)) {
            throw new Error('Availability request failed.');
        }

        const availability = await Promise.all(responses.map(response => response.json()));
        bookedSlots = availability.flatMap(data => data.slots || []);
        ensureSelectedSlotIsAvailable();
        renderSlots();
        updateSummary();
    } catch (e) {
        console.error('Error fetching availability:', e);
        bookedSlots = [{ start: '07:00', end: '22:00' }];
        availabilityError.innerText = 'Court availability could not be loaded. Please try again before booking.';
        availabilityError.classList.remove('hidden');
        ensureSelectedSlotIsAvailable();
        renderSlots();
        updateSummary();
    }
}

function getSelectedCourtInputs() {
    return Array.from(document.querySelectorAll('input[name="court_ids[]"]:checked'));
}

function incrementEquipment(btn) {
    const id = Number(btn.dataset.id);
    const maxStock = Number(btn.dataset.stock);
    const input = document.getElementById('qty_' + id);
    let val = parseInt(input.value) || 0;
    if (val < maxStock) {
        input.value = val + 1;
        updateSummary();
    }
}

function decrementEquipment(btn) {
    const id = Number(btn.dataset.id);
    const input = document.getElementById('qty_' + id);
    let val = parseInt(input.value) || 0;
    if (val > 0) {
        input.value = val - 1;
        updateSummary();
    }
}

function updateSummary() {
    const selectedCourts = getSelectedCourtInputs();
    const courtRate = selectedCourts.reduce((total, input) => total + parseFloat(input.dataset.rate), 0);
    const courtName = selectedCourts.length
        ? selectedCourts.map(input => input.dataset.name).join(', ')
        : 'Select at least one court';

    const dateVal = document.getElementById('reservationDate').value;
    const durationLabel = `${selectedDurationHours} ${selectedDurationHours === 1 ? 'hour' : 'hours'}`;

    document.getElementById('summaryCourtName').innerText = courtName;
    document.getElementById('summaryDate').innerText = dateVal;
    document.getElementById('summaryTime').innerText = selectedSlot.start
        ? `${selectedSlot.start} - ${selectedSlot.end}`
        : 'No available time';
    document.getElementById('summaryDuration').innerText = durationLabel;
    document.getElementById('submitBtn').disabled = selectedCourts.length === 0 || !selectedSlot.start;

    const courtFee = courtRate * selectedDurationHours;
    document.getElementById('summaryCourtFee').innerText = `$${courtFee.toFixed(2)}`;

    // Calculate gear fees
    let gearFee = 0;
    let gearItems = [];

    for (const [id, eq] of Object.entries(equipmentData)) {
        const input = document.getElementById('qty_' + id);
        if (input) {
            const qty = parseInt(input.value) || 0;
            if (qty > 0) {
                const itemTotal = qty * parseFloat(eq.price);
                gearFee += itemTotal;
                gearItems.push(`${qty}x ${eq.name} ($${itemTotal.toFixed(2)})`);
            }
        }
    }

    document.getElementById('summaryGearFee').innerText = `$${gearFee.toFixed(2)}`;

    const gearListEl = document.getElementById('summaryGearList');
    if (gearItems.length > 0) {
        gearListEl.classList.remove('hidden');
        gearListEl.innerHTML = gearItems.map(item => `<div>• ${item}</div>`).join('');
    } else {
        gearListEl.classList.add('hidden');
        gearListEl.innerHTML = '';
    }

    const total = courtFee + gearFee;
    document.getElementById('summaryTotalFee').innerText = `$${total.toFixed(2)}`;
}

// Initial initialization
document.addEventListener('DOMContentLoaded', () => {
    ensureSelectedSlotIsAvailable();
    renderSlots();
    updateSummary();
});
</script>
@endpush
@endsection
