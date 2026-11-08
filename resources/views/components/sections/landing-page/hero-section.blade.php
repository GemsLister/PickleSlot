<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-emerald-900 via-slate-900 to-slate-900 py-20 sm:py-28 text-white">
    <!-- Court Lines Decorative Background -->
    <div class="pointer-events-none absolute inset-0 opacity-10">
        <div class="mx-auto h-full max-w-5xl border-2 border-white/60">
            <div class="h-1/2 border-b-2 border-white/60">
                <div class="h-full border-r-2 border-white/60 w-1/2"></div>
            </div>
            <div class="h-1/4 border-b-2 border-white/60 bg-white/5"></div>
        </div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-4 py-1.5 text-xs font-semibold text-emerald-300 backdrop-blur-md mb-6">
            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Premium Pickleball Courts & Official Rental Gear
        </span>

        <h1 class="mx-auto max-w-4xl text-4xl font-extrabold tracking-tight sm:text-6xl lg:text-7xl">
            Book Your Court. <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-lime-300 bg-clip-text text-transparent">Rent Pro Gear.</span> Play Today.
        </h1>

        <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-300 sm:text-xl">
            PickleSlot makes reserving pickleball courts effortless. Reserve indoor climate-controlled or scenic outdoor courts, rent tournament-grade carbon paddles, and pick up fresh balls on arrival.
        </p>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-7 py-3.5 text-base font-bold text-white shadow-lg shadow-emerald-500/25 hover:from-emerald-400 hover:to-teal-400 transition-all hover:scale-105">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Book a Court Now
            </a>
            <a href="{{ route('courts.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-base font-semibold text-white backdrop-blur-md hover:bg-white/20 transition">
                Browse Courts
            </a>
            <a href="{{ route('equipment.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-base font-semibold text-white backdrop-blur-md hover:bg-white/20 transition">
                View Paddles & Balls
            </a>
        </div>

        <!-- Quick Stats Banner -->
        <div class="mt-16 grid grid-cols-2 gap-4 sm:grid-cols-4 max-w-4xl mx-auto border-t border-white/10 pt-10 text-left">
            <div class="rounded-xl bg-white/5 p-4 backdrop-blur-sm border border-white/10">
                <p class="text-2xl font-bold text-emerald-400">5 Courts</p>
                <p class="text-xs text-slate-400 mt-1">Indoor & Outdoor Championship</p>
            </div>
            <div class="rounded-xl bg-white/5 p-4 backdrop-blur-sm border border-white/10">
                <p class="text-2xl font-bold text-emerald-400">Pro Carbon</p>
                <p class="text-xs text-slate-400 mt-1">Paddle Rentals Ready</p>
            </div>
            <div class="rounded-xl bg-white/5 p-4 backdrop-blur-sm border border-white/10">
                <p class="text-2xl font-bold text-emerald-400">7 AM - 10 PM</p>
                <p class="text-xs text-slate-400 mt-1">Daily Facility Access</p>
            </div>
            <div class="rounded-xl bg-white/5 p-4 backdrop-blur-sm border border-white/10">
                <p class="text-2xl font-bold text-emerald-400">Instant</p>
                <p class="text-xs text-slate-400 mt-1">Conflict-Free Confirmation</p>
            </div>
        </div>
    </div>
</section>
