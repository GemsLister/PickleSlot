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
            <x-forms.register-form />
        </div>
    </div>
</div>
@endsection

