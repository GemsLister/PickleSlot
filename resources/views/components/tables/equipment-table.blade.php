<table class="min-w-full divide-y divide-slate-200 text-sm">
    <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
        <tr>
            <th class="py-3.5 px-4 text-left">Equipment Name</th>
            <th class="py-3.5 px-4 text-center">Category</th>
            <th class="py-3.5 px-4 text-right">Rental Price</th>
            <th class="py-3.5 px-4 text-center">In Stock</th>
            <th class="py-3.5 px-4 text-center">Status</th>
            <th class="py-3.5 px-4 text-right">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100 bg-white">
        @foreach ($equipment as $item)
        <tr>
            <td class="py-4 px-4 font-bold text-slate-900">
                {{ $item->name }}
                <p class="text-xs font-normal text-slate-500 line-clamp-1">{{ $item->description }}</p>
            </td>
            <td class="py-4 px-4 text-center">
                <span class="rounded-md px-2.5 py-0.5 text-xs font-bold capitalize {{ $item->category === 'paddle' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ $item->category }}
                </span>
            </td>
            <td class="py-4 px-4 text-right font-black text-slate-900">${{ number_format($item->price, 2) }}</td>
            <td class="py-4 px-4 text-center font-bold text-slate-800">{{ $item->stock }}</td>
            <td class="py-4 px-4 text-center">
                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $item->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                    {{ $item->is_active ? 'Active' : 'Disabled' }}
                </span>
            </td>
            <td class="py-4 px-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.equipment.edit', $item) }}" class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.equipment.destroy', $item) }}" onsubmit="return confirm('Delete this equipment item?')">
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