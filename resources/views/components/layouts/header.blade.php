<!-- Top Navigation -->
<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur-md">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
            <img src="{{asset('images/pickleslot-logo.png')}}" alt="pickleslot-logo" class="h-10 w-auto">
            <span class="flex flex-col">
                <span class="text-xl font-bold tracking-tight text-slate-900 leading-none">Pickle<span class="text-emerald-600">Slot</span></span>
                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mt-0.5">Court & Gear Rentals</span>
            </span>
        </a>

        <!-- Desktop Links -->
        <nav class="hidden md:flex items-center gap-1 sm:gap-2">
            @guest
            <a href="{{ route('courts.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('courts.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                Courts
            </a>
            <a href="{{ route('equipment.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('equipment.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                Gear Rental
            </a>
            <a href="{{ route('reservations.create') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('reservations.create') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                Book a Slot
            </a>
            @else
            @if(!Auth::user()->is_admin)
            <a href="{{ route('courts.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('courts.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                Courts
            </a>
            <a href="{{ route('equipment.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('equipment.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                Gear Rental
            </a>
            <a href="{{ route('reservations.create') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('reservations.create') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                Book a Slot
            </a>
            <a href="{{ route('reservations.index') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('reservations.index') || request()->routeIs('reservations.show') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                My Bookings
            </a>
            @endif
            <a href="{{ route('profile.edit') }}" class="rounded-lg px-3.5 py-2 text-sm font-medium {{ request()->routeIs('profile.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                My Profile
            </a>
            @if(Auth::user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-sm font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200/60 transition">
                <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Admin Center
            </a>
            @endif
            @endguest
        </nav>

        <!-- User Auth Buttons & Mobile Menu Toggle -->
        <div class="flex items-center gap-2.5">
            @guest
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-emerald-600 px-3 py-2 transition">
                Log in
            </a>
            <x-buttons.primary-button href="{{ route('register') }}">
                Sign up
            </x-buttons.primary-button>
            @else
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-sm font-semibold text-slate-900 leading-tight">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-slate-500">{{ Auth::user()->is_admin ? 'Administrator' : 'Player' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:text-red-600 transition" title="Log out">
                        Log out
                    </button>
                </form>
            </div>
            @endguest

            <!-- Mobile Menu Button -->
            <button type="button" onclick="toggleMobileNav()" class="md:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">
                <svg id="menuOpenIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="menuCloseIcon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobileNav" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-1">
        @guest
        <a href="{{ route('courts.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('courts.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            Courts
        </a>
        <a href="{{ route('equipment.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('equipment.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            Gear Rental
        </a>
        <a href="{{ route('reservations.create') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('reservations.create') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            Book a Slot
        </a>
        @else
        @if(!Auth::user()->is_admin)
        <a href="{{ route('courts.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('courts.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            Courts
        </a>
        <a href="{{ route('equipment.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('equipment.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            Gear Rental
        </a>
        <a href="{{ route('reservations.create') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('reservations.create') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            Book a Slot
        </a>
        <a href="{{ route('reservations.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('reservations.index') || request()->routeIs('reservations.show') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            My Bookings
        </a>
        @endif
        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('profile.*') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:bg-slate-50' }}">
            My Profile
        </a>
        @if(Auth::user()->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-amber-800 bg-amber-50">
            Admin Center
        </a>
        @endif
        @endguest
    </div>
</header>