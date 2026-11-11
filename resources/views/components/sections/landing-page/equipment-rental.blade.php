@props(['equipment'])
<!-- Equipment Rental Showcase -->
<section class="py-16 sm:py-24 bg-slate-50 border-t border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:mb-12 md:flex-row md:items-end md:justify-between">
            <div data-reveal>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Don't have gear? We've got you
                    covered</span>
                <h2 class="mt-1 text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">Paddles & Balls
                    Rental</h2>
                <p class="mt-2 text-slate-600 max-w-2xl">Add high-performance carbon paddles or official tournament
                    balls directly to your court booking.</p>
            </div>
            <a data-reveal href="{{ route('equipment.index') }}"
                class="inline-flex items-center gap-1.5 self-start text-sm font-semibold text-emerald-600 transition hover:gap-2.5 hover:text-emerald-700 md:self-auto">
                Explore full inventory
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
                <button type="button" data-carousel-prev aria-label="Show previous gear" aria-controls="gearCarousel" disabled class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" />
                    </svg>
                </button>
                <button type="button" data-carousel-next aria-label="Show next gear" aria-controls="gearCarousel" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>
        </div>
        <div id="gearCarousel" data-carousel role="region" aria-label="Rental gear carousel" tabindex="0" class="scrollbar-hidden -mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain px-4 pb-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 sm:mx-0 sm:grid sm:grid-cols-2 sm:gap-6 sm:overflow-visible sm:px-0 sm:pb-0 lg:grid-cols-4">
            @foreach ($equipment as $item)
                <div data-reveal style="--reveal-delay: {{ ($loop->index % 4) * 80 }}ms" class="group flex w-[min(84vw,20rem)] shrink-0 snap-center flex-col justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg sm:w-auto sm:shrink sm:p-5">
                    <div>
                        @if ($item->image_url)
                            <div class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105">
                            </div>
                        @endif

                        <div class="mb-3 flex items-center justify-between gap-2">
                            <span
                                class="inline-flex items-center rounded-lg px-2 py-0.5 text-xs font-semibold {{ $item->category === 'paddle' ? 'bg-purple-50 text-purple-700 border border-purple-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }} capitalize">
                                {{ $item->category }}
                            </span>
                            <span
                                class="rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-600">
                                {{ $item->stock }} in stock
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-slate-900">{{ $item->name }}</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">{{ $item->description }}</p>
                    </div>

                    <div class="mt-5 flex items-center justify-between gap-2 border-t border-slate-100 pt-3 sm:gap-3">
                        <div>
                            <span class="text-xs text-slate-400">Rental fee</span>
                            <p class="text-lg font-bold text-slate-900">
                                ${{ number_format($item->price, 2) }}
                                <span class="text-xs font-normal text-slate-500">/ session</span>
                            </p>
                        </div>
                        <x-buttons.primary-button href="{{ route('reservations.create') }}" class="shrink-0 whitespace-nowrap text-xs sm:text-sm">
                            Add to booking
                        </x-buttons.primary-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>