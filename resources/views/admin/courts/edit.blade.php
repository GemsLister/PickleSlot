@extends('layouts.app')

@section('title', 'Edit ' . $court->name . ' - Admin')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.courts.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Back to courts</a>
        <h1 class="text-2xl font-black text-slate-900 mt-2">Edit Court: {{ $court->name }}</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <x-forms.edit-court-form :court="$court" />
    </div>
</div>
@endsection

