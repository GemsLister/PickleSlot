<!-- How It Works Section -->
<section class="py-16 sm:py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">How PickleSlot Works</h2>
        <p class="mt-3 text-slate-600 max-w-xl mx-auto">Get on court in three easy steps with real-time slot verification.</p>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-lg mb-4">1</div>
                <h3 class="text-lg font-bold text-slate-900">Select Court & Slot</h3>
                <p class="mt-2 text-sm text-slate-600">Pick from indoor air-conditioned or outdoor courts and choose your preferred date and 1-hour time blocks.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-lg mb-4">2</div>
                <h3 class="text-lg font-bold text-slate-900">Add Paddles & Balls</h3>
                <p class="mt-2 text-sm text-slate-600">Select paddle rentals (Selkirk, Joola) and can of tournament balls with automatic inventory reservation.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-lg mb-4">3</div>
                <h3 class="text-lg font-bold text-slate-900">Confirm & Play</h3>
                <p class="mt-2 text-sm text-slate-600">Receive instant booking confirmation. Your court and freshly prepped gear will be ready when you walk in.</p>
            </div>
        </div>

        <div class="mt-12">
            <x-buttons.primary-button href="{{ route('reservations.create') }}">
                Start Reservation
            </x-buttons.primary-button>
        </div>
    </div>
</section>