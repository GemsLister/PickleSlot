<div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
    <div class="flex items-center gap-3 mb-6">
        <span
            class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">1</span>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Choose Courts</h2>
            <p class="text-xs text-slate-500">Select every court you want to reserve for the same session.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="courtList">
        @foreach ($courts as $court)
            <label
                class="court-card relative flex flex-col justify-between rounded-xl border-2 p-4 cursor-pointer transition-all {{ in_array($court->id, $selectedCourtIds, true) ? 'border-emerald-600 bg-emerald-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300' }}">
                <input type="checkbox" name="court_ids[]" value="{{ $court->id }}" data-rate="{{ $court->hourly_rate }}"
                    data-name="{{ $court->name }}" class="sr-only" {{ in_array($court->id, $selectedCourtIds, true) ? 'checked' : '' }} onchange="onCourtChange()">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <span
                            class="rounded-md px-2 py-0.5 text-[11px] font-bold uppercase {{ $court->type === 'indoor' ? 'bg-blue-100 text-blue-800' : ($court->type === 'covered' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                            {{ $court->type }}
                        </span>
                        <span
                            class="font-extrabold text-slate-900 text-base">₱{{ number_format($court->hourly_rate, 2) }}<span
                                class="text-xs font-normal text-slate-500">/hr</span></span>
                    </div>
                    <h3 class="mt-2 text-base font-bold text-slate-900 leading-snug">{{ $court->name }}</h3>
                    <p class="mt-1 text-xs text-slate-500">{{ $court->surface }}</p>
                </div>
                <div
                    class="mt-3 flex items-center gap-1.5 text-xs text-emerald-700 font-semibold selection-indicator {{ in_array($court->id, $selectedCourtIds, true) ? 'opacity-100' : 'opacity-0' }}">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                            clip-rule="evenodd" />
                    </svg>
                    Selected
                </div>
            </label>
        @endforeach
    </div>
    @if ($errors->has('court_ids') || $errors->has('court_ids.*'))
        <p class="mt-3 text-sm font-medium text-red-600">
            {{ $errors->first('court_ids') ?: $errors->first('court_ids.*') }}
        </p>
    @endif
</div>