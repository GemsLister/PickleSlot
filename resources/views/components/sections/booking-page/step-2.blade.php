<div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
    <div class="flex items-center gap-3 mb-6">
        <span
            class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">2</span>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Select Date & Time Slot</h2>
            <p class="text-xs text-slate-500">Facility operating hours: 07:00 AM – 10:00 PM</p>
        </div>
    </div>

    <!-- Date Picker with Quick Jump Buttons -->
    <div class="space-y-3">
        <label for="reservationDate" class="block text-sm font-semibold text-slate-700">Reservation
            Date</label>
        <div class="flex flex-wrap items-center gap-3">
            <input type="date" id="reservationDate" name="date" min="{{ now()->format('Y-m-d') }}"
                value="{{ $selectedDate }}"
                class="rounded-xl border border-slate-300 px-3.5 py-2 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                onchange="onDateChange()">

            <button type="button" onclick="setQuickDate(0)"
                class="rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                Today
            </button>
            <button type="button" onclick="setQuickDate(1)"
                class="rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
                Tomorrow
            </button>
            <button type="button" onclick="setQuickDate(2)"
                class="rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition">
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
                    <span class="h-3 w-3 rounded bg-slate-100 border border-slate-200"></span>
                    Unavailable
                </span>
            </div>
        </div>

        <!-- Slots Grid -->
        <div id="slotsGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
            <!-- Populated dynamically via JS -->
        </div>
        <p id="availabilityError" class="mt-3 hidden text-sm font-medium text-red-600" role="alert"></p>

        <div class="mt-5 max-w-xs">
            <label for="durationHours" class="block text-sm font-semibold text-slate-700">Reservation
                Duration</label>
            <select id="durationHours" name="duration_hours" required onchange="onDurationChange()"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                @for ($duration = 1; $duration <= 15; $duration++)
                    <option value="{{ $duration }}" {{ (int) old('duration_hours', 1) === $duration ? 'selected' : '' }}>
                        {{ $duration }} {{ $duration === 1 ? 'hour' : 'hours' }}
                    </option>
                @endfor
            </select>
            <p class="mt-1 text-xs text-slate-500">Choose consecutive hours. Availability and price
                update automatically.</p>
        </div>

        <!-- Hidden time inputs sent to backend -->
        <input type="hidden" id="startTimeInput" name="start_time" value="{{ old('start_time', '09:00') }}" required>
        <input type="hidden" id="endTimeInput" name="end_time" value="{{ old('end_time', '10:00') }}" required>

        <div
            class="mt-4 rounded-xl bg-slate-50 p-3.5 flex items-center justify-between text-xs text-slate-600 border border-slate-100">
            <span>Selected Session:</span>
            <span id="selectedSlotSummary" class="font-bold text-slate-900 text-sm">
                {{ old('start_time', '09:00') }} - {{ old('end_time', '10:00') }}
                ({{ old('duration_hours', 1) }}
                {{ (int) old('duration_hours', 1) === 1 ? 'hour' : 'hours' }})
            </span>
        </div>
    </div>
</div>