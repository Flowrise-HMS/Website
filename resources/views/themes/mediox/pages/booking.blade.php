@extends('website::themes.mediox.layouts.app')

@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp

@section('title', $metaTitle ?? 'Book Appointment')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="page-header__inner">
                <div class="page-header__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/page-header-bg.jpg') }});"></div>
                <div class="container">
                    <div class="page-header__content">
                        <h2 class="page-header__title">Book an Appointment</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="appointment-one section-space-bottom">
        <div class="appointment-one__bg mediox-jarallax" data-jarallax data-speed="0.3s" style="background-image: url({{ $themes->assetUrl('images/backgrounds/appointment-bg.jpg') }});">
            <div class="appointment-one__bg__inner" style="background-image: url({{ $themes->assetUrl('images/shapes/appointment-shape-bg.png') }});"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="appointment-one__content">
                        <h3 class="appointment-one__title">Book An Appointment</h3>
                        @livewire(\Modules\Website\Livewire\BookingWizard::class)
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
