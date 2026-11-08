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
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label for="court_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Filter by Court</label>
                <select id="court_id" name="court_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-emerald-500 focus:outline-none">
                    <option value="">All Courts</option>
                    @foreach ($courts as $court)
                        <option value="{{ $court->id }}" {{ (string) $courtId === (string) $court->id ? 'selected' : '' }}>{{ $court->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Filter by Status</label>
                <select id="status" name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-emerald-500 focus:outline-none">
                    <option value="">All Statuses</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label for="date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Filter by Date</label>
                <input id="date" name="date" type="date" value="{{ $date }}" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-emerald-500 focus:outline-none">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-900 text-white py-2 px-4 text-xs font-bold hover:bg-slate-800 transition">
                    Apply Filter
                </button>
                <a href="{{ route('admin.reservations.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 py-2 px-3 text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
        @if ($reservations->isEmpty())
            <div class="p-12 text-center text-sm text-slate-500">
                No reservations found matching the selected filters.
            </div>
        @else
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                    <tr>
                        <th class="py-3.5 px-4 text-left">Ref #</th>
                        <th class="py-3.5 px-4 text-left">Court</th>
                        <th class="py-3.5 px-4 text-left">Player</th>
                        <th class="py-3.5 px-4 text-left">Schedule</th>
                        <th class="py-3.5 px-4 text-left">Rented Gear</th>
                        <th class="py-3.5 px-4 text-right">Total Fee</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($reservations as $res)
                        <tr>
                            <td class="py-4 px-4 font-bold text-slate-900">
                                <a href="{{ route('reservations.show', $res) }}" class="text-emerald-600 hover:underline">
                                    #{{ $res->id }}
                                </a>
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-800">
                                {{ $res->court->name }}
                                <span class="block text-xs font-normal text-slate-400 capitalize">{{ $res->court->type }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-semibold text-slate-900">{{ $res->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $res->user->phone ?? $res->user->email }}</p>
                            </td>
                            <td class="py-4 px-4 text-xs text-slate-600">
                                <span class="font-bold text-slate-800">{{ $res->date->format('M d, Y') }}</span>
                                <span class="block text-slate-500">{{ substr((string) $res->start_time, 0, 5) }} – {{ substr((string) $res->end_time, 0, 5) }}</span>
                            </td>
                            <td class="py-4 px-4 text-xs text-slate-600">
                                @forelse ($res->reservationEquipments as $item)
                                    <div><strong>{{ $item->quantity }}x</strong> {{ $item->equipment->name }}</div>
                                @empty
                                    <span class="text-slate-400">None</span>
                                @endforelse
                            </td>
                            <td class="py-4 px-4 text-right font-black text-slate-900">${{ number_format($res->total_price, 2) }}</td>
                            <td class="py-4 px-4 text-center">
                                <span class="rounded-md px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider {{ $res->status === 'confirmed' ? 'bg-emerald-50 text-emerald-700' : ($res->status === 'completed' ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $res->status }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <form method="POST" action="{{ route('admin.reservations.status', $res) }}" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-lg border border-slate-200 py-1 px-2 text-xs text-slate-700 focus:outline-none">
                                        <option value="confirmed" {{ $res->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ $res->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $res->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="rounded-lg bg-slate-100 hover:bg-slate-200 py-1 px-2 text-xs font-bold text-slate-700">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

