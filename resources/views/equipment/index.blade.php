@extends('layouts.app')

@section('title', 'Paddles & Balls Rental - PickleSlot')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Paddles & Balls Rental</h1>
            <p class="mt-2 text-base text-slate-600">Upgrade your match with tournament-ready equipment. Add paddles and balls during your court booking.</p>
        </div>
        <x-buttons.primary-button href="{{ route('reservations.create') }}">
            Rent with Court Booking
        </x-buttons.primary-button>
    </div>

    <!-- Category Tabs -->
    <div class="mt-6 flex items-center gap-2">
        <a href="{{ route('equipment.index') }}" class="rounded-xl px-4 py-2 text-xs font-semibold {{ empty($category) ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
            All Equipment
        </a>
        <a href="{{ route('equipment.index', ['category' => 'paddle']) }}" class="rounded-xl px-4 py-2 text-xs font-semibold {{ $category === 'paddle' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
            Paddles Only
        </a>
        <a href="{{ route('equipment.index', ['category' => 'balls']) }}" class="rounded-xl px-4 py-2 text-xs font-semibold {{ $category === 'balls' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }} transition">
            Balls Only
        </a>
    </div>

    <!-- Paddles Section -->
    @if(empty($category) || $category === 'paddle')
        <x-sections.rental-page.paddle-rental :paddles="$paddles" />
    @endif

    <!-- Balls Section -->
    @if(empty($category) || $category === 'balls')
        <x-sections.rental-page.balls-rental :balls="$balls" />
    @endif
</div>
@endsection

