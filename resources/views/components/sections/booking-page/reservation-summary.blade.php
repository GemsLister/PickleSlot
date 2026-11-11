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
                <span id="summaryCourtFee" class="font-bold text-slate-900">₱0.00</span>
            </div>

            <!-- Gear Subtotal -->
            <div class="flex items-center justify-between">
                <span class="text-slate-600">Gear Rental:</span>
                <span id="summaryGearFee" class="font-bold text-slate-900">₱0.00</span>
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
                <span id="summaryTotalFee" class="text-2xl font-black text-emerald-600">₱0.00</span>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 pt-4 border-t border-slate-100">
            <button type="submit" id="submitBtn"
                class="flex w-full justify-center items-center gap-2 rounded-xl bg-emerald-600 px-4 py-3.5 text-base font-bold text-white shadow-md hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition">
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