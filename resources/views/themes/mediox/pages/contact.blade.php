@extends('website::themes.mediox.layouts.app')

@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp

@section('title', $metaTitle ?? 'Contact')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="page-header__inner">
                <div class="page-header__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/page-header-bg.jpg') }});"></div>
                <div class="container">
                    <div class="page-header__content">
                        <h2 class="page-header__title">Contact Us</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-page section-space-top">
        <div class="contact-page__inner section-space">
            <div class="container">
                <div class="row gutter-y-60">
                    <div class="col-lg-8">
                        <div class="contact-page__form">
                            @livewire(\Modules\Website\Livewire\ContactForm::class)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
