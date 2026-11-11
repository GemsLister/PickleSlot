const equipmentDataElement = document.getElementById('equipment-data');
const bookedSlotsElement = document.getElementById('booked-slots-data');

if (equipmentDataElement && bookedSlotsElement) {
    const equipmentData = JSON.parse(equipmentDataElement.textContent);
    let bookedSlots = JSON.parse(bookedSlotsElement.textContent);

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
        document.getElementById('summaryCourtFee').innerText = `₱${courtFee.toFixed(2)}`;

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
                    gearItems.push(`${qty}x ${eq.name} (₱${itemTotal.toFixed(2)})`);
                }
            }
        }

        document.getElementById('summaryGearFee').innerText = `₱${gearFee.toFixed(2)}`;

        const gearListEl = document.getElementById('summaryGearList');
        if (gearItems.length > 0) {
            gearListEl.classList.remove('hidden');
            gearListEl.replaceChildren(...gearItems.map(item => {
                const itemElement = document.createElement('div');
                itemElement.textContent = `• ${item}`;

                return itemElement;
            }));
        } else {
            gearListEl.classList.add('hidden');
            gearListEl.replaceChildren();
        }

        const total = courtFee + gearFee;
        document.getElementById('summaryTotalFee').innerText = `₱${total.toFixed(2)}`;
    }

    Object.assign(window, {
        onCourtChange,
        onDateChange,
        onDurationChange,
        setQuickDate,
        incrementEquipment,
        decrementEquipment,
    });

    // Initial initialization
    document.addEventListener('DOMContentLoaded', () => {
        ensureSelectedSlotIsAvailable();
        renderSlots();
        updateSummary();
    });
}