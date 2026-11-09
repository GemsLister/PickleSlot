<footer class="mt-auto border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/pickleslot-logo.png') }}" alt="pickleslot-logo" class="h-6 w-6">
                <span class="text-sm font-semibold text-slate-900">PickleSlot</span>
                <span class="text-xs text-slate-400">© {{ date('Y') }} All rights reserved.</span>
            </div>
            <div class="flex items-center gap-6 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Facility Open Daily 07:00 - 22:00
                </span>
                @guest
                    <a href="{{ route('courts.index') }}" class="hover:text-slate-900">All Courts</a>
                    <a href="{{ route('equipment.index') }}" class="hover:text-slate-900">Paddles & Balls</a>
                    <a href="{{ route('reservations.create') }}" class="hover:text-slate-900">Instant Booking</a>
                @else
                    @if(!Auth::user()->is_admin)
                        <a href="{{ route('courts.index') }}" class="hover:text-slate-900">All Courts</a>
                        <a href="{{ route('equipment.index') }}" class="hover:text-slate-900">Paddles & Balls</a>
                        <a href="{{ route('reservations.create') }}" class="hover:text-slate-900">Instant Booking</a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</footer>