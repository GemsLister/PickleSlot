@extends('layouts.app')

@section('title', 'Pickleball Courts & Equipment Rental')

@section('content')
    <x-sections.landing-page.hero-section />
    <x-sections.landing-page.court-rental :courts="$courts" />
    <x-sections.landing-page.equipment-rental :equipment="$equipment" />
    <x-sections.landing-page.steps-section />
@endsection