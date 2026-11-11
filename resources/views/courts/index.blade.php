@extends('layouts.app')

@section('title', 'Pickleball Courts - PickleSlot')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-8">
            <div data-reveal>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Our Pickleball Courts</h1>
                <p class="mt-2 text-base text-slate-600">Explore regulation size courts with premium surfaces, LED lighting,
                    and weather-protected play. You can select multiple courts together when booking.</p>
            </div>
        </div>

        <!-- Filter Pills -->
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <a data-reveal style="--reveal-delay: 80ms" href="{{ route('courts.index') }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ empty($type) ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                All Courts ({{ count($courts) }})
            </a>
            <a data-reveal style="--reveal-delay: 80ms" href="{{ route('courts.index', ['type' => 'indoor']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $type === 'indoor' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Indoor (Climate Controlled)
            </a>
            <a data-reveal style="--reveal-delay: 160ms" href="{{ route('courts.index', ['type' => 'outdoor']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $type === 'outdoor' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Outdoor Open-Air
            </a>
            <a data-reveal style="--reveal-delay: 240ms" href="{{ route('courts.index', ['type' => 'covered']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $type === 'covered' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Covered Pavilion
            </a>
        </div>

        <!-- Courts Carousel -->
        @if (count($courts) > 1)
            <div class="mb-3 mt-8 flex items-center justify-between sm:hidden">
                <p class="flex items-center gap-2 text-xs font-medium text-slate-500">
                    Swipe to explore
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6" />
                    </svg>
                </p>
                <div class="flex gap-2">
                    <button type="button" data-carousel-prev aria-label="Show previous court" aria-controls="courtsIndexCarousel" disabled class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6" />
                        </svg>
                    </button>
                    <button type="button" data-carousel-next aria-label="Show next court" aria-controls="courtsIndexCarousel" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700 disabled:cursor-not-allowed disabled:opacity-40">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
        <div id="courtsIndexCarousel" data-carousel role="region" aria-label="Available courts carousel" tabindex="0" class="{{ count($courts) > 1 ? 'mt-3 sm:mt-8' : 'mt-8' }} scrollbar-hidden -mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain px-4 pb-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 sm:mx-0 sm:grid sm:grid-cols-2 sm:gap-6 sm:overflow-visible sm:px-0 sm:pb-0 lg:grid-cols-3">
            @forelse ($courts as $court)
                <div data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 90 }}ms"
                    class="group flex w-[min(84vw,20rem)] shrink-0 snap-center flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg sm:w-auto sm:shrink sm:p-6">
                    <div>
                        @if ($court->image_url)
                            <div class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img src="{{ $court->image_url }}" alt="{{ $court->name }}" loading="lazy" class="h-40 w-full object-cover">
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span
                                class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold {{ $court->type === 'indoor' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : ($court->type === 'covered' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60') }} capitalize">
                                {{ $court->type }}
                            </span>
                            <span class="text-2xl font-black text-slate-900">₱{{ number_format($court->hourly_rate, 2) }} <span
                                    class="text-xs font-medium text-slate-500">/hr</span></span>
                        </div>

                        <h2 class="text-xl font-bold text-slate-900">{{ $court->name }}</h2>

                        <div class="mt-3 flex items-center gap-1.5 text-xs text-slate-500">
                            <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Surface: <strong class="text-slate-700">{{ $court->surface }}</strong></span>
                        </div>

                        <p class="mt-4 text-sm text-slate-600 leading-relaxed">{{ $court->description }}</p>
                    </div>

                    <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-4">
                        <x-buttons.primary-button href="{{ route('reservations.create', ['court_id' => $court->id]) }}">
                            Select & Book
                        </x-buttons.primary-button>
                        <x-buttons.secondary-button href="{{ route('courts.show', $court) }}">
                            View Schedule
                        </x-buttons.secondary-button>
                    </div>
                </div>
            @empty
                <div class="w-full rounded-2xl border border-dashed border-slate-300 p-12 text-center sm:col-span-full">
                    <p class="text-slate-500">No courts found matching this filter.</p>
                    <a href="{{ route('courts.index') }}" class="mt-3 inline-block text-sm font-semibold text-emerald-600">View
                        all courts</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection