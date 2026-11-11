<div class="overflow-x-auto" role="region" aria-label="Equipment and stock management table. Scroll horizontally to view all columns." tabindex="0">
    <table class="min-w-[680px] divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
            <tr>
                <th class="whitespace-nowrap px-4 py-3.5 text-left">Equipment Name</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-center">Category</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-right">Rental Price</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-center">In Stock</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-center">Status</th>
                <th class="whitespace-nowrap px-4 py-3.5 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @foreach ($equipment as $item)
            <tr>
                <td class="px-4 py-4 font-bold text-slate-900">
                    {{ $item->name }}
                    <p class="line-clamp-1 text-xs font-normal text-slate-500">{{ $item->description }}</p>
                </td>
                <td class="px-4 py-4 text-center">
                    <span class="whitespace-nowrap rounded-md px-2.5 py-0.5 text-xs font-bold capitalize {{ $item->category === 'paddle' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $item->category }}
                    </span>
                </td>
                <td class="whitespace-nowrap px-4 py-4 text-right font-black text-slate-900">₱{{ number_format($item->price, 2) }}</td>
                <td class="px-4 py-4 text-center font-bold text-slate-800">{{ $item->stock }}</td>
                <td class="px-4 py-4 text-center">
                    <span class="whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-bold {{ $item->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                        {{ $item->is_active ? 'Active' : 'Disabled' }}
                    </span>
                </td>
                <td class="px-4 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <x-buttons.edit-button :href="route('admin.equipment.edit', $item)" />
                        <x-buttons.delete-button :action="route('admin.equipment.destroy', $item)" type="equipment item" />
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>