<div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
    <div class="flex items-center gap-3 mb-2">
        <span
            class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-sm">3</span>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Rental Equipment Add-ons</h2>
            <p class="text-xs text-slate-500">Need paddles or fresh balls? Add them below and pick up at
                the court desk.</p>
        </div>
    </div>

    <!-- Paddles Group -->
    <div class="mt-6">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Paddle Rentals</h3>
        <div class="space-y-3">
            @foreach ($paddles as $paddle)
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-slate-300 transition">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-slate-900 text-sm">{{ $paddle->name }}</h4>
                            <span
                                class="rounded bg-purple-50 text-purple-700 px-1.5 py-0.5 text-[10px] font-semibold uppercase">Paddle</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $paddle->description }}</p>
                        <p class="text-xs font-semibold text-emerald-600 mt-1">
                            ₱{{ number_format($paddle->price, 2) }} / session <span
                                class="text-slate-400 font-normal">({{ $paddle->stock }} in stock)</span>
                        </p>
                    </div>

                    <!-- Stepper Counter -->
                    <div class="flex items-center gap-2">
                        <button type="button" data-id="{{ $paddle->id }}" onclick="decrementEquipment(this)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">-</button>
                        <input type="number" id="qty_{{ $paddle->id }}" name="equipment[{{ $paddle->id }}]"
                            value="{{ old('equipment.' . $paddle->id, 0) }}" min="0" max="{{ $paddle->stock }}" readonly
                            class="w-12 text-center rounded-lg border border-slate-200 py-1 text-sm font-bold text-slate-900 focus:outline-none bg-slate-50">
                        <button type="button" data-id="{{ $paddle->id }}" data-stock="{{ $paddle->stock }}"
                            onclick="incrementEquipment(this)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">+</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Balls Group -->
    <div class="mt-8">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ball Rentals & Sets
        </h3>
        <div class="space-y-3">
            @foreach ($balls as $ball)
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 hover:border-slate-300 transition">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-slate-900 text-sm">{{ $ball->name }}</h4>
                            <span
                                class="rounded bg-amber-50 text-amber-800 px-1.5 py-0.5 text-[10px] font-semibold uppercase">Balls</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $ball->description }}</p>
                        <p class="text-xs font-semibold text-emerald-600 mt-1">
                            ₱{{ number_format($ball->price, 2) }} / session <span
                                class="text-slate-400 font-normal">({{ $ball->stock }} available)</span></p>
                    </div>

                    <!-- Stepper Counter -->
                    <div class="flex items-center gap-2">
                        <button type="button" data-id="{{ $ball->id }}" onclick="decrementEquipment(this)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">-</button>
                        <input type="number" id="qty_{{ $ball->id }}" name="equipment[{{ $ball->id }}]"
                            value="{{ old('equipment.' . $ball->id, 0) }}" min="0" max="{{ $ball->stock }}" readonly
                            class="w-12 text-center rounded-lg border border-slate-200 py-1 text-sm font-bold text-slate-900 focus:outline-none bg-slate-50">
                        <button type="button" data-id="{{ $ball->id }}" data-stock="{{ $ball->stock }}"
                            onclick="incrementEquipment(this)"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100 font-bold transition">+</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>