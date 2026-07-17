@extends('website::themes.default.layouts.app')
@section('title', $metaTitle ?? 'Contact')
@section('content')
<section class="website-section" data-reveal>
    <div class="website-container">
        <h1 class="website-section__title">Contact Us</h1>
        @livewire(\Modules\Website\Livewire\ContactForm::class)
    </div>
</section>
@endsection
