@extends('website::themes.mediox.layouts.app')

@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $resolveMedia = function (?string $path): ?string {
        if (empty($path)) {
            return null;
        }
        if (preg_match('#^(https?://|/)#', $path)) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($path)
            ? \Illuminate\Support\Facades\Storage::url($path)
            : asset($path);
    };
@endphp

@section('title', $metaTitle ?? 'Partners')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="page-header__inner">
                <div class="page-header__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/page-header-bg.jpg') }});"></div>
                <div class="container">
                    <div class="page-header__content">
                        <h2 class="page-header__title">Partners</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="client-carousel section-space">
        <div class="container">
            <div class="client-carousel__one">
                @foreach ($partners as $partner)
                    <div class="client-carousel__one__item">
                        @php $logoUrl = $resolveMedia($partner->logo); @endphp
                        @if ($logoUrl)
                            @if ($partner->url)
                                <a href="{{ $partner->url }}" rel="noopener" target="_blank">
                                    <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="client-carousel__one__image">
                                </a>
                            @else
                                <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="client-carousel__one__image">
                            @endif
                        @else
                            <div class="client-carousel__one__item">
                                @if ($partner->url)
                                    <a href="{{ $partner->url }}" rel="noopener" target="_blank">{{ $partner->name }}</a>
                                @else
                                    {{ $partner->name }}
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
