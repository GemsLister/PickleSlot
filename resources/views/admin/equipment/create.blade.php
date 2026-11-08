@extends('layouts.app')

@section('title', 'Add Equipment - Admin')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.equipment.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Back to equipment inventory</a>
        <h1 class="text-2xl font-black text-slate-900 mt-2">Add New Equipment Item</h1>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <x-forms.create-equipment-form />
    </div>
</div>
@endsection

