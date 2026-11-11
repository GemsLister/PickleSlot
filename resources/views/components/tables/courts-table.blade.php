<div class="overflow-x-auto" role="region" aria-label="Courts management table. Scroll horizontally to view all columns." tabindex="0">
    <table class="min-w-[760px] divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
            <tr>
                <th class="whitespace-nowrap px-4 py-3.5 text-left">Court Name</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-center">Type</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-left">Surface</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-right">Hourly Rate</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-center">Status</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-center">Bookings</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @foreach ($courts as $court)
            <tr>
                <td class="whitespace-nowrap px-4 py-4 font-bold text-slate-900">{{ $court->name }}</td>
                <td class="px-4 py-4 text-center">
                    <span class="rounded-md px-2 py-0.5 text-xs font-bold capitalize {{ $court->type === 'indoor' ? 'bg-blue-50 text-blue-700' : ($court->type === 'covered' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                        {{ $court->type }}
                    </span>
                </td>
                <td class="whitespace-nowrap px-4 py-4 text-xs text-slate-600">{{ $court->surface }}</td>
                <td class="whitespace-nowrap px-4 py-4 text-right font-black text-slate-900">₱{{ number_format($court->hourly_rate, 2) }}</td>
                <td class="px-4 py-4 text-center">
                    <form method="POST" action="{{ route('admin.courts.toggle', $court) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-bold {{ $court->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $court->is_active ? 'Active' : 'Disabled' }}
                        </button>
                    </form>
                </td>
                <td class="px-4 py-4 text-center font-bold text-slate-700">{{ $court->reservations_count }}</td>
                <td class="px-4 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <x-buttons.edit-button :href="route('admin.courts.edit', $court)" />
                        <x-buttons.delete-button :action="route('admin.courts.destroy', $court)" type="court" />
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>