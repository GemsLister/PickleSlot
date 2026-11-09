<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <label for="notes" class="block text-sm font-semibold text-slate-700">Special Requests / Player
        Notes <span class="text-xs font-normal text-slate-400">(optional)</span></label>
    <textarea id="notes" name="notes" rows="2"
        placeholder="e.g., Playing doubles practice, left-handed paddle preferred, etc."
        class="mt-2 block w-full rounded-xl border border-slate-300 p-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('notes') }}</textarea>
</div>