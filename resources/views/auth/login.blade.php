@extends('layouts.app')

@section('title', 'Log In - PickleSlot')

@section('content')
<div class="flex min-h-[calc(100vh-14rem)] items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('images/pickleslot-logo.png') }}" alt="pickleslot-logo" class="h-16 w-auto">
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">Sign in to PickleSlot</h2>
            <p class="mt-2 text-sm text-slate-600">
                Or <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-500">create a new player account</a>
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <x-forms.login-form />

            @if (app()->environment('local'))
            <!-- Quick Demo Credentials Fill -->
            <div class="mt-6 border-t border-slate-200 pt-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 text-center mb-3">Quick Demo Login</p>
                <div class="grid grid-cols-2 gap-2.5">
                    <button type="button" onclick="fillCredentials('admin@pickleslot.com', 'password')" class="rounded-lg border border-amber-200 bg-amber-50/70 hover:bg-amber-100 p-2 text-left text-xs transition">
                        <p class="font-bold text-amber-900">Admin Account</p>
                        <p class="text-amber-700 truncate">admin@pickleslot.com</p>
                    </button>
                    <button type="button" onclick="fillCredentials('player@pickleslot.com', 'password')" class="rounded-lg border border-emerald-200 bg-emerald-50/70 hover:bg-emerald-100 p-2 text-left text-xs transition">
                        <p class="font-bold text-emerald-900">Player Account</p>
                        <p class="text-emerald-700 truncate">player@pickleslot.com</p>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if (app()->environment('local'))
<script>
function fillCredentials(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
@endif
@endsection

