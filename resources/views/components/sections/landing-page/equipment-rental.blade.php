@props(['equipment'])
<!-- Equipment Rental Showcase -->
<section class="py-16 sm:py-24 bg-slate-50 border-t border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Don't have gear? We've got you
                    covered</span>
                <h2 class="mt-1 text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">Paddles & Balls
                    Rental</h2>
                <p class="mt-2 text-slate-600 max-w-2xl">Add high-performance carbon paddles or official tournament
                    balls directly to your court booking.</p>
            </div>
            <a href="{{ route('equipment.index') }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                Explore full inventory
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($equipment as $item)
                <div class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-300 hover:shadow-lg">
                    <div>
                        @if ($item->image_url)
                            <div class="mb-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="h-36 w-full object-cover">
                            </div>
                        @endif

                        <div class="mb-3 flex items-center justify-between gap-2">
                            <span
                                class="inline-flex items-center rounded-lg px-2 py-0.5 text-xs font-semibold {{ $item->category === 'paddle' ? 'bg-purple-50 text-purple-700 border border-purple-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }} capitalize">
                                {{ $item->category }}
                            </span>
                            <span
                                class="rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-600">
                                {{ $item->stock }} in stock
                            </span>
                        </div>

                        <h3 class="text-base font-bold text-slate-900">{{ $item->name }}</h3>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">{{ $item->description }}</p>
                    </div>

                    <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                        <div>
                            <span class="text-xs text-slate-400">Rental fee</span>
                            <p class="text-lg font-bold text-slate-900">
                                ${{ number_format($item->price, 2) }}
                                <span class="text-xs font-normal text-slate-500">/ session</span>
                            </p>
                        </div>
                        <x-buttons.primary-button href="{{ route('reservations.create') }}">
                            Add to booking
                        </x-buttons.primary-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>