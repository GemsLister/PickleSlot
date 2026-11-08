<form action="{{ route('admin.equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    <div>
        <label for="name" class="block text-sm font-semibold text-slate-700">Equipment Name</label>
        <input id="name" name="name" type="text" required value="{{ old('name', $equipment->name) }}"
            class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="category" class="block text-sm font-semibold text-slate-700">Category</label>
            <select id="category" name="category" required
                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                <option value="paddle" {{ old('category', $equipment->category) === 'paddle' ? 'selected' : '' }}>Paddle</option>
                <option value="balls" {{ old('category', $equipment->category) === 'balls' ? 'selected' : '' }}>Balls Pack</option>
            </select>
        </div>

        <div>
            <label for="price" class="block text-sm font-semibold text-slate-700">Rental Price ($/session)</label>
            <input id="price" name="price" type="number" step="0.50" min="0" required value="{{ old('price', $equipment->price) }}"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
        </div>

        <div>
            <label for="stock" class="block text-sm font-semibold text-slate-700">Available Stock</label>
            <input id="stock" name="stock" type="number" min="0" required value="{{ old('stock', $equipment->stock) }}"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-slate-700">Item Description</label>
        <textarea id="description" name="description" rows="3"
            class="mt-1 block w-full rounded-xl border border-slate-300 p-3 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('description', $equipment->description) }}</textarea>
    </div>

    <div>
        <label for="image" class="block text-sm font-semibold text-slate-700">Equipment Image</label>
        <input id="image" name="image" type="file" accept="image/*"
            class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
        <p class="mt-1 text-xs text-slate-500">Upload a new image to replace the current one. Supported: JPG, PNG, GIF, WEBP.</p>

        @if ($equipment->image_url)
            <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Current image</div>
                <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}" class="h-28 w-full rounded-lg object-cover">
            </div>
        @endif
    </div>

    @if ($equipment->image_url)
        <div class="flex items-center gap-2">
            <input id="remove_image" name="remove_image" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            <label for="remove_image" class="text-sm font-medium text-slate-700">Remove current image</label>
        </div>
    @endif

    <div class="flex items-center gap-2">
        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $equipment->is_active) ? 'checked' : '' }}
            class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
        <label for="is_active" class="text-sm font-medium text-slate-700">Item is active and available for customer rental</label>
    </div>

    <div class="pt-4 flex items-center justify-end gap-3">
        <a href="{{ route('admin.equipment.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancel</a>
        <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition">Update Equipment</button>
    </div>
</form>