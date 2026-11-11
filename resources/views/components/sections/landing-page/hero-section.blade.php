<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-emerald-900 via-slate-900 to-slate-900 py-16 text-white sm:py-20 lg:py-28">
    <!-- Court Lines Decorative Background -->
    <div class="pointer-events-none absolute inset-0 opacity-10">
        <div class="mx-auto h-full max-w-5xl border-2 border-white/60">
            <div class="h-1/2 border-b-2 border-white/60">
                <div class="h-full border-r-2 border-white/60 w-1/2"></div>
            </div>
            <div class="h-1/4 border-b-2 border-white/60 bg-white/5"></div>
        </div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        <span data-reveal class="mb-6 inline-flex max-w-full items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-4 py-1.5 text-xs font-semibold text-emerald-300 backdrop-blur-md">
            <span class="h-2 w-2 shrink-0 rounded-full bg-emerald-400 motion-safe:animate-pulse"></span>
            Premium Pickleball Courts & Official Rental Gear
        </span>

        <h1 data-reveal style="--reveal-delay: 100ms" class="mx-auto max-w-4xl text-balance text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl md:text-6xl lg:text-7xl">
            Book Your Court. <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-lime-300 bg-clip-text text-transparent">Rent Pro Gear.</span> Play Today.
        </h1>

        <p data-reveal style="--reveal-delay: 180ms" class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-slate-300 sm:mt-6 sm:text-lg lg:text-xl">
            PickleSlot makes reserving pickleball courts effortless. Reserve indoor climate-controlled or scenic outdoor courts, rent tournament-grade carbon paddles, and pick up fresh balls on arrival.
        </p>

        <div data-reveal style="--reveal-delay: 260ms" class="mx-auto mt-8 flex max-w-xl flex-col items-stretch justify-center gap-3 sm:mt-10 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4">
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-3.5 text-base font-bold text-white shadow-lg shadow-emerald-500/25 transition duration-200 hover:from-emerald-400 hover:to-teal-400 hover:shadow-xl hover:shadow-emerald-500/30 sm:px-7 motion-safe:hover:scale-[1.03]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Book a Court Now
            </a>
            <a href="{{ route('courts.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-base font-semibold text-white backdrop-blur-md transition duration-200 hover:bg-white/20">
                Browse Courts
            </a>
            <a href="{{ route('equipment.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-base font-semibold text-white backdrop-blur-md transition duration-200 hover:bg-white/20">
                View Paddles & Balls
            </a>
        </div>

        <!-- Quick Stats Banner -->
        <div data-reveal style="--reveal-delay: 340ms" class="mx-auto mt-12 grid max-w-4xl grid-cols-2 gap-3 border-t border-white/10 pt-8 text-left sm:mt-16 sm:gap-4 sm:pt-10 lg:grid-cols-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur-sm transition duration-200 hover:-translate-y-1 hover:bg-white/10 sm:p-4">
                <p class="text-lg font-bold text-emerald-400 sm:text-2xl">5 Courts</p>
                <p class="text-xs text-slate-400 mt-1">Indoor & Outdoor Championship</p>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur-sm transition duration-200 hover:-translate-y-1 hover:bg-white/10 sm:p-4">
                <p class="text-lg font-bold text-emerald-400 sm:text-2xl">Pro Carbon</p>
                <p class="text-xs text-slate-400 mt-1">Paddle Rentals Ready</p>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur-sm transition duration-200 hover:-translate-y-1 hover:bg-white/10 sm:p-4">
                <p class="text-lg font-bold text-emerald-400 sm:text-2xl">7 AM - 10 PM</p>
                <p class="text-xs text-slate-400 mt-1">Daily Facility Access</p>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur-sm transition duration-200 hover:-translate-y-1 hover:bg-white/10 sm:p-4">
                <p class="text-lg font-bold text-emerald-400 sm:text-2xl">Instant</p>
                <p class="text-xs text-slate-400 mt-1">Conflict-Free Confirmation</p>
            </div>
        </div>
    </div>
</section>
