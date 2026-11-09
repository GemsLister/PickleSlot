@extends('layouts.app')

@section('title', 'My Profile - PickleSlot')

@section('content')
    <div class="bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Account settings</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">My Profile</h1>
                    <p class="mt-2 text-sm text-slate-600">Manage your personal information and account security.</p>
                </div>
                <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-sm font-semibold {{ $user->is_admin ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                    {{ $user->is_admin ? 'Administrator' : 'Player' }}
                </span>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-slate-900">Personal information</h2>
                        <p class="mt-1 text-sm text-slate-500">Update the details associated with your account.</p>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Full name</label>
                            <input id="name" name="name" type="text" autocomplete="name" required
                                value="{{ old('name', $user->name) }}"
                                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email', $user->email) }}"
                                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700">
                                Phone number <span class="font-normal text-slate-400">(optional)</span>
                            </label>
                            <input id="phone" name="phone" type="tel" autocomplete="tel"
                                value="{{ old('phone', $user->phone) }}"
                                class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border-t border-slate-100 pt-5">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                                Save changes
                            </button>
                        </div>
                    </form>
                </section>

                <div class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-6">
                            <h2 class="text-lg font-bold text-slate-900">Change password</h2>
                            <p class="mt-1 text-sm text-slate-500">Confirm your current password before choosing a new one.</p>
                        </div>

                        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-slate-700">Current password</label>
                                <input id="current_password" name="current_password" type="password" autocomplete="current-password" required
                                    class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700">New password</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                    class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm new password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                    class="mt-1 block w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            </div>

                            <div class="border-t border-slate-100 pt-5">
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                                    Update password
                                </button>
                            </div>
                        </form>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-sm font-bold text-slate-900">Account details</h2>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-slate-500">Member since</dt>
                                <dd class="font-medium text-slate-700">{{ $user->created_at->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-slate-500">Account type</dt>
                                <dd class="font-medium text-slate-700">{{ $user->is_admin ? 'Administrator' : 'Player' }}</dd>
                            </div>
                        </dl>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
