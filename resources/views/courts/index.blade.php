@extends('layouts.app')

@section('title', 'Pickleball Courts - PickleSlot')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Our Pickleball Courts</h1>
                <p class="mt-2 text-base text-slate-600">Explore regulation size courts with premium surfaces, LED lighting,
                    and weather-protected play. You can select multiple courts together when booking.</p>
            </div>
            <!-- <a href="{{ route('reservations.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition">
                    Book Any Court
                </a> -->
        </div>

        <!-- Filter Pills -->
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <a href="{{ route('courts.index') }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ empty($type) ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                All Courts ({{ count($courts) }})
            </a>
            <a href="{{ route('courts.index', ['type' => 'indoor']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $type === 'indoor' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Indoor (Climate Controlled)
            </a>
            <a href="{{ route('courts.index', ['type' => 'outdoor']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $type === 'outdoor' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Outdoor Open-Air
            </a>
            <a href="{{ route('courts.index', ['type' => 'covered']) }}"
                class="rounded-xl px-4 py-2 text-xs font-semibold {{ $type === 'covered' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
                Covered Pavilion
            </a>
        </div>

        <!-- Courts Grid -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($courts as $court)
                <div
                    class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-lg hover:border-emerald-300 transition duration-150">
                    <div>
                        @if ($court->image_url)
                            <div class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img src="{{ $court->image_url }}" alt="{{ $court->name }}" class="h-40 w-full object-cover">
                            </div>
                        @endif

                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span
                                class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold {{ $court->type === 'indoor' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : ($court->type === 'covered' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60') }} capitalize">
                                {{ $court->type }}
                            </span>
                            <span class="text-2xl font-black text-slate-900">${{ number_format($court->hourly_rate, 2) }} <span
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
                        <a href="{{ route('reservations.create', ['court_id' => $court->id]) }}"
                            class="flex-1 rounded-xl bg-emerald-600 py-2.5 text-center text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition">
                            Select & Book
                        </a>
                        <a href="{{ route('courts.show', $court) }}"
                            class="rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                            View Schedule
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-12 text-center">
                    <p class="text-slate-500">No courts found matching this filter.</p>
                    <a href="{{ route('courts.index') }}" class="mt-3 inline-block text-sm font-semibold text-emerald-600">View
                        all courts</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection