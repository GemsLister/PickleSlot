@extends('layouts.app')

@section('title', 'Manage Gear & Inventory - Admin')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-6 mb-8">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Admin Overview</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl mt-1">Equipment Inventory & Pricing</h1>
            <p class="mt-1 text-sm text-slate-600">Track paddle rentals, tournament ball sets, rental fees, and available stock.</p>
        </div>
        <a href="{{ route('admin.equipment.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-500 transition">
            + Add Equipment Item
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
        <x-tables.equipment-table :equipment="$equipment" />
    </div>
</div>
@endsection

