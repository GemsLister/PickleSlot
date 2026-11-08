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
                {{ $res->displayCourts()->pluck('name')->join(', ') }}
                <span class="block text-xs font-normal text-slate-400 capitalize">{{ $res->displayCourts()->pluck('type')->unique()->join(', ') }}</span>
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