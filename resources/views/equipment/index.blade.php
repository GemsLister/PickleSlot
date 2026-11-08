@extends('layouts.app')

@section('title', 'Paddles & Balls Rental - PickleSlot')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Paddles & Balls Rental</h1>
            <p class="mt-2 text-base text-slate-600">Upgrade your match with tournament-ready equipment. Add paddles and balls during your court booking.</p>
        </div>
        <a href="{{ route('reservations.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition">
            Rent with Court Booking
        </a>
    </div>

    <!-- Category Tabs -->
    <div class="mt-6 flex items-center gap-2">
        <a href="{{ route('equipment.index') }}" class="rounded-xl px-4 py-2 text-xs font-semibold {{ empty($category) ? 'bg-slate-900 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
            All Equipment
        </a>
        <a href="{{ route('equipment.index', ['category' => 'paddle']) }}" class="rounded-xl px-4 py-2 text-xs font-semibold {{ $category === 'paddle' ? 'bg-purple-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
            Paddles Only
        </a>
        <a href="{{ route('equipment.index', ['category' => 'balls']) }}" class="rounded-xl px-4 py-2 text-xs font-semibold {{ $category === 'balls' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
            Balls Only
        </a>
    </div>

    <!-- Paddles Section -->
    @if(empty($category) || $category === 'paddle')
        <div class="mt-10">
            <div class="flex items-center gap-2 mb-6">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-700 font-bold">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l5.5 5.5a2.121 2.121 0 01-3 3L11 13M11 13L6.5 8.5a4.5 4.5 0 016.364-6.364L14 3.25M3 21l3.5-3.5" />
                    </svg>
                </span>
                <h2 class="text-2xl font-bold text-slate-900">Rental Paddles</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($paddles as $paddle)
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                        <div>
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
                                <p class="text-xl font-bold text-slate-900">${{ number_format($paddle->price, 2) }} <span class="text-xs font-normal text-slate-500">/ session</span></p>
                            </div>
                            <a href="{{ route('reservations.create') }}" class="rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 px-3.5 py-2 text-xs font-bold transition">
                                Rent in Booking
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Balls Section -->
    @if(empty($category) || $category === 'balls')
        <div class="mt-14">
            <div class="flex items-center gap-2 mb-6">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-amber-700 font-bold">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="9" stroke-width="2" />
                    </svg>
                </span>
                <h2 class="text-2xl font-bold text-slate-900">Official Pickleballs</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($balls as $ball)
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="rounded-lg bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 text-xs font-semibold uppercase">
                                    Balls Pack
                                </span>
                                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                                    {{ $ball->stock }} Packs In Stock
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-slate-900">{{ $ball->name }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $ball->description }}</p>
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                            <div>
                                <span class="text-xs text-slate-400">Rental Price</span>
                                <p class="text-xl font-bold text-slate-900">${{ number_format($ball->price, 2) }} <span class="text-xs font-normal text-slate-500">/ session</span></p>
                            </div>
                            <a href="{{ route('reservations.create') }}" class="rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-3.5 py-2 text-xs font-bold transition">
                                Rent in Booking
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

