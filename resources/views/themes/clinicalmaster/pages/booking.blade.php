@extends('website::themes.clinicalmaster.layouts.app')

@section('title', $metaTitle ?? 'Book Appointment')

@section('content')
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => 'Book an Appointment'])

    <section class="cm-page-body">
        <div class="container cm-booking-panel">
            <h2 class="cm-booking-panel__title">Schedule Your Visit</h2>
            @livewire(\Modules\Website\Livewire\BookingWizard::class)
        </div>
    </section>
@endsection
