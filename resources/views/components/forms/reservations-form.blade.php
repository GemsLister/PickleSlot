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