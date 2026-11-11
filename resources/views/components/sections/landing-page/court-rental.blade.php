@props(['courts'])
<!-- Featured Courts Section -->
<section class="py-16 sm:py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:mb-12 md:flex-row md:items-end md:justify-between">
            <div data-reveal>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">State of the Art Facilities</span>
                <h2 class="mt-1 text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">Available Courts</h2>
                <p class="mt-2 text-slate-600 max-w-2xl">Each court is engineered with tournament regulation cushioning, high-visibility boundaries, and professional lighting.</p>
            </div>
            <a data-reveal href="{{ route('courts.index') }}" class="inline-flex items-center gap-1.5 self-start text-sm font-semibold text-emerald-600 transition hover:gap-2.5 hover:text-emerald-700 md:self-auto">
                View all courts
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="mb-3 flex items-center justify-between sm:hidden">
            <p class="flex items-center gap-2 text-xs font-medium text-slate-500">
                Swipe to explore
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                </svg>
            </p>
            <div class="flex gap-2">
                <button type="button" data-carousel-prev aria-label="Show previous court" aria-controls="courtCarousel" disabled class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" />
                    </svg>
                </button>
                <button type="button" data-carousel-next aria-label="Show next court" aria-controls="courtCarousel" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>
        </div>
        <div id="courtCarousel" data-carousel role="region" aria-label="Available courts carousel" tabindex="0" class="scrollbar-hidden -mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain px-4 pb-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 sm:mx-0 sm:grid sm:grid-cols-2 sm:gap-6 sm:overflow-visible sm:px-0 sm:pb-0 lg:grid-cols-3">
            @foreach ($courts as $court)
                <div data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 90 }}ms" class="group relative flex w-[min(84vw,20rem)] shrink-0 snap-center flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-xl sm:w-auto sm:shrink sm:p-6">
                    <div>
                        <!-- Type & Rate Badge -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold {{ $court->type === 'indoor' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : ($court->type === 'covered' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60') }} capitalize">
                                {{ $court->type }}
                            </span>
                            <span class="text-xl font-bold text-slate-900">${{ number_format($court->hourly_rate, 2) }}<span class="text-xs font-normal text-slate-500">/hr</span></span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $court->name }}</h3>
                        <p class="mt-2 text-xs font-medium text-slate-500 flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Surface: {{ $court->surface }}
                        </p>
                        <p class="mt-3 text-sm text-slate-600 line-clamp-2">{{ $court->description }}</p>
                    </div>

                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-4">
                        <x-buttons.primary-button href="{{ route('reservations.create', ['court_id' => $court->id]) }}" class="flex-1">
                            Book Court
                        </x-buttons.primary-button>
                        <x-buttons.secondary-button href="{{ route('courts.show', $court) }}">
                            Details
                        </x-buttons.secondary-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>