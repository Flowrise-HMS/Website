@extends('website::themes.clinicalmaster.layouts.app')

@section('title', $metaTitle ?? 'Contact')

@section('content')
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => 'Contact Us'])

    <section class="py-15 lg:py-20">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 md:p-10">
                        @livewire(\Modules\Website\Livewire\ContactForm::class)
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
