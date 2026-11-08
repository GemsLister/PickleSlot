<form action="{{ route('admin.courts.update', $court) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    <div>
        <label for="name" class="block text-sm font-semibold text-slate-700">Court Name</label>
        <input id="name" name="name" type="text" required value="{{ old('name', $court->name) }}"
            class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="type" class="block text-sm font-semibold text-slate-700">Court Type</label>
            <select id="type" name="type" required
                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                <option value="indoor" {{ old('type', $court->type) === 'indoor' ? 'selected' : '' }}>Indoor (Climate Controlled)</option>
                <option value="outdoor" {{ old('type', $court->type) === 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                <option value="covered" {{ old('type', $court->type) === 'covered' ? 'selected' : '' }}>Covered Pavilion</option>
            </select>
        </div>

        <div>
            <label for="hourly_rate" class="block text-sm font-semibold text-slate-700">Hourly Rate ($)</label>
            <input id="hourly_rate" name="hourly_rate" type="number" step="0.50" min="0" required value="{{ old('hourly_rate', $court->hourly_rate) }}"
                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
        </div>
    </div>

    <div>
        <label for="surface" class="block text-sm font-semibold text-slate-700">Surface Specification</label>
        <input id="surface" name="surface" type="text" required value="{{ old('surface', $court->surface) }}"
            class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-slate-700">Description & Amenities</label>
        <textarea id="description" name="description" rows="3"
            class="mt-1 block w-full rounded-xl border border-slate-300 p-3 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('description', $court->description) }}</textarea>
    </div>

    <div>
        <label for="image" class="block text-sm font-semibold text-slate-700">Court Image</label>
        <input id="image" name="image" type="file" accept="image/*"
            class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
        <p class="mt-1 text-xs text-slate-500">Upload a new image to replace the current one. Supported: JPG, PNG, GIF, WEBP.</p>

        @if ($court->image_url)
            <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Current image</div>
                <img src="{{ $court->image_url }}" alt="{{ $court->name }}" class="h-28 w-full rounded-lg object-cover">
            </div>
        @endif
    </div>

    @if ($court->image_url)
        <div class="flex items-center gap-2">
            <input id="remove_image" name="remove_image" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            <label for="remove_image" class="text-sm font-medium text-slate-700">Remove current image</label>
        </div>
    @endif

    <div class="flex items-center gap-2">
        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $court->is_active) ? 'checked' : '' }}
            class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
        <label for="is_active" class="text-sm font-medium text-slate-700">Court is active and open for booking</label>
    </div>

    <div class="pt-4 flex items-center justify-end gap-3">
        <a href="{{ route('admin.courts.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancel</a>
        <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition">Update Court</button>
    </div>
</form>