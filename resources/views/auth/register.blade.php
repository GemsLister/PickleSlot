@extends('layouts.app')

@section('title', 'Sign Up - PickleSlot')

@section('content')
    <div class="flex min-h-[calc(100vh-14rem)] items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('images/pickleslot-logo.png') }}" alt="pickleslot-logo" class="h-16 w-auto">
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">Create your player account</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Already registered? <a href="{{ route('login') }}"
                        class="font-semibold text-emerald-600 hover:text-emerald-500">Sign in here</a>
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                <x-forms.register-form />
            </div>
        </div>
    </div>
@endsection