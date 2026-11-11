<section class="mt-10">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 font-bold text-purple-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l5.5 5.5a2.121 2.121 0 01-3 3L11 13M11 13L6.5 8.5a4.5 4.5 0 016.364-6.364L14 3.25M3 21l3.5-3.5" />
                </svg>
            </span>
            <h2 class="text-2xl font-bold text-slate-900">Rental Paddles</h2>
        </div>
        <div class="flex gap-2 sm:hidden">
            <button type="button" data-carousel-prev aria-label="Show previous paddle" aria-controls="paddleCarousel" disabled class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" />
                </svg>
            </button>
            <button type="button" data-carousel-next aria-label="Show next paddle" aria-controls="paddleCarousel" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                </svg>
            </button>
        </div>
    </div>

    <p class="mb-3 flex items-center gap-2 text-xs font-medium text-slate-500 sm:hidden">
        Swipe to explore
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
        </svg>
    </p>
    <div id="paddleCarousel" data-carousel role="region" aria-label="Rental paddles carousel" tabindex="0" class="scrollbar-hidden -mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain px-4 pb-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 sm:mx-0 sm:grid sm:grid-cols-2 sm:gap-6 sm:overflow-visible sm:px-0 sm:pb-0 lg:grid-cols-3">
        @foreach ($paddles as $paddle)
        <div data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 90 }}ms" class="flex w-[min(84vw,20rem)] shrink-0 snap-center flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg sm:w-auto sm:shrink sm:p-6">
            <div>
                @if ($paddle->image_url)
                    <div class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        <img src="{{ $paddle->image_url }}" alt="{{ $paddle->name }}" loading="lazy" class="h-48 w-full object-cover">
                    </div>
                @else
                    <div class="mb-4 flex h-48 items-center justify-center rounded-xl border border-slate-200 bg-gradient-to-br from-purple-50 to-slate-100 text-purple-600">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l5.5 5.5a2.121 2.121 0 01-3 3L11 13M11 13L6.5 8.5a4.5 4.5 0 016.364-6.364L14 3.25M3 21l3.5-3.5" />
                        </svg>
                    </div>
                @endif

                <div class="flex items-center justify-between mb-3">
                    <span class="rounded-lg bg-purple-50 text-purple-700 border border-purple-200 px-2.5 py-0.5 text-xs font-semibold uppercase">
                        Paddle
                    </span>
                    <span class="text-xs font-semibold {{ $paddle->stock > 5 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-amber-700 bg-amber-50 border-amber-200' }} border px-2 py-0.5 rounded-full">
                        {{ $paddle->stock }} Available
                    </span>
                </div>

                <h3 class="text-lg font-bold text-slate-900">{{ $paddle->name }}</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $paddle->description }}</p>
            </div>

            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                <div>
                    <span class="text-xs text-slate-400">Rental Price</span>
                    <p class="text-xl font-bold text-slate-900">₱{{ number_format($paddle->price, 2) }} <span class="text-xs font-normal text-slate-500">/ session</span></p>
                </div>
                <x-buttons.primary-button href="{{ route('reservations.create') }}">
                    Rent in Booking
                </x-buttons.primary-button>
            </div>
        </div>
        @endforeach
    </div>
</section>