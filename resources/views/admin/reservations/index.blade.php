@extends('layouts.app')

@section('title', 'Manage All Bookings - Admin')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-6 mb-8">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Admin Overview</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl mt-1">Court Reservations</h1>
            <p class="mt-1 text-sm text-slate-600">Filter, review, and update booking status for all facility reservations.</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm mb-6">
        <x-forms.reservations-form :courts="$courts" :court-id="$courtId" :status="$status" :date="$date" />
    </div>

    <!-- Reservations Table -->
    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
        <x-tables.reservations-table :reservations="$reservations" />
    </div>
</div>
@endsection

