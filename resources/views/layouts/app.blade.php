<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PickleSlot') }} - @yield('title', 'Pickleball Court Reservation & Equipment Rental')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col font-sans text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">
    <!-- Top Navigation -->
    <x-layouts.header />
    <!-- Main Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>
    <!-- Footer -->
    <x-layouts.footer />
    <script>
    function toggleMobileNav() {
        const nav = document.getElementById('mobileNav');
        const menuButton = document.querySelector('[aria-controls="mobileNav"]');
        const header = menuButton.closest('header');
        const openIcon = document.getElementById('menuOpenIcon');
        const closeIcon = document.getElementById('menuCloseIcon');
        const shouldOpen = menuButton.getAttribute('aria-expanded') !== 'true';

        nav.inert = !shouldOpen;
        nav.setAttribute('aria-hidden', String(!shouldOpen));
        nav.dataset.open = String(shouldOpen);
        header.dataset.mobileNavOpen = String(shouldOpen);
        menuButton.setAttribute('aria-expanded', String(shouldOpen));
        menuButton.setAttribute('aria-label', shouldOpen ? 'Close navigation menu' : 'Open navigation menu');
        openIcon.classList.toggle('hidden', shouldOpen);
        closeIcon.classList.toggle('hidden', !shouldOpen);
    }
    </script>
    <!-- SweetAlert2 notifications -->
    <x-notifications.sweet-alert />
    @stack('scripts')
</body>
</html>
