<section class="mt-10">
    <div class="flex items-center gap-2 mb-6">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 text-purple-700 font-bold">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l5.5 5.5a2.121 2.121 0 01-3 3L11 13M11 13L6.5 8.5a4.5 4.5 0 016.364-6.364L14 3.25M3 21l3.5-3.5" />
            </svg>
        </span>
        <h2 class="text-2xl font-bold text-slate-900">Rental Paddles</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($paddles as $paddle)
        <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">
            <div>
                @if ($paddle->image_url)
                    <div class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        <img src="{{ $paddle->image_url }}" alt="{{ $paddle->name }}" class="h-48 w-full object-cover">
                    </div>
                @else
                    <div class="mb-4 flex h-48 items-center justify-center rounded-xl border border-slate-200 bg-gradient-to-br from-purple-50 to-slate-100 text-purple-600">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l5.5 5.5a2.121 2.121 0 01-3 3L11 13M11 13L6.5 8.5a4.5 4.5 0 016.364-6.364L14 3.25M3 21l3.5-3.5" />
                        </svg>
                    </div>
                @endif

                <div class="flex items-center justify-between mb-3">
                    <span class="rounded-lg bg-purple-50 text-purple-700 border border-purple-200 px-2.5 py-0.5 text-xs font-semibold uppercase">
                        Paddle
                    </span>
                    <span class="text-xs font-semibold {{ $paddle->stock > 5 ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-amber-700 bg-amber-50 border-amber-200' }} border px-2 py-0.5 rounded-full">
                        {{ $paddle->stock }} Available
                    </span>
                </div>

                <h3 class="text-lg font-bold text-slate-900">{{ $paddle->name }}</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $paddle->description }}</p>
            </div>

            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                <div>
                    <span class="text-xs text-slate-400">Rental Price</span>
                    <p class="text-xl font-bold text-slate-900">${{ number_format($paddle->price, 2) }} <span class="text-xs font-normal text-slate-500">/ session</span></p>
                </div>
                <x-buttons.primary-button href="{{ route('reservations.create') }}">
                    Rent in Booking
                </x-buttons.primary-button>
            </div>
        </div>
        @endforeach
    </div>
</section>