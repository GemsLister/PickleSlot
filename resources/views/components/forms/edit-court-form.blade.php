<form action="{{ route('admin.courts.update', $court) }}" method="POST" class="space-y-5">
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