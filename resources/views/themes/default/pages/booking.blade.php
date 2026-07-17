@extends('website::themes.default.layouts.app')
@section('title', $metaTitle ?? 'Book Appointment')
@section('content')
<section class="website-section" data-reveal>
    <div class="website-container">
        <h1 class="website-section__title">Book an Appointment</h1>
        @livewire(\Modules\Website\Livewire\BookingWizard::class)
    </div>
</section>
@endsection
