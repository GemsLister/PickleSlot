@extends('layouts.app')

@section('title', 'Sign Up - PickleSlot')

@section('content')
<div class="flex min-h-[calc(100vh-14rem)] items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <div class="text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white shadow-md shadow-emerald-500/20">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="15.5" cy="8.5" r="3.5" fill="#facc15" stroke="#ca8a04" stroke-width="1.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l5.5 5.5a2.121 2.121 0 01-3 3L11 13" />
                </svg>
            </span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">Create your player account</h2>
            <p class="mt-2 text-sm text-slate-600">
                Already registered? <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-500">Sign in here</a>
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="Alex Rivera">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="alex@example.com">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700">Phone Number <span class="text-xs font-normal text-slate-400">(optional)</span></label>
                    <div class="mt-1">
                        <input id="phone" name="phone" type="tel" autocomplete="tel" value="{{ old('phone') }}"
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="+1 (555) 014-9982">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="At least 8 characters">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                            class="block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="Repeat password">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 transition">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

