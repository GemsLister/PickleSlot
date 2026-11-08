@extends('layouts.app')

@section('title', 'Manage Courts - Admin')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-6 mb-8">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Admin Overview</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl mt-1">Manage Courts</h1>
            <p class="mt-1 text-sm text-slate-600">Add, configure, or toggle availability for pickleball courts.</p>
        </div>
        <a href="{{ route('admin.courts.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-500 transition">
            + Add New Court
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                <tr>
                    <th class="py-3.5 px-4 text-left">Court Name</th>
                    <th class="py-3.5 px-4 text-center">Type</th>
                    <th class="py-3.5 px-4 text-left">Surface</th>
                    <th class="py-3.5 px-4 text-right">Hourly Rate</th>
                    <th class="py-3.5 px-4 text-center">Status</th>
                    <th class="py-3.5 px-4 text-center">Bookings</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @foreach ($courts as $court)
                    <tr>
                        <td class="py-4 px-4 font-bold text-slate-900">{{ $court->name }}</td>
                        <td class="py-4 px-4 text-center">
                            <span class="rounded-md px-2 py-0.5 text-xs font-bold capitalize {{ $court->type === 'indoor' ? 'bg-blue-50 text-blue-700' : ($court->type === 'covered' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                {{ $court->type }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-slate-600 text-xs">{{ $court->surface }}</td>
                        <td class="py-4 px-4 text-right font-black text-slate-900">${{ number_format($court->hourly_rate, 2) }}</td>
                        <td class="py-4 px-4 text-center">
                            <form method="POST" action="{{ route('admin.courts.toggle', $court) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $court->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $court->is_active ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-4 text-center font-bold text-slate-700">{{ $court->reservations_count }}</td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.courts.edit', $court) }}" class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.courts.destroy', $court) }}" onsubmit="return confirm('Delete court {{ $court->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-red-200 text-red-600 px-2.5 py-1 text-xs font-semibold hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

