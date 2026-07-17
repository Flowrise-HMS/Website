@extends('website::themes.mediox.layouts.app')

@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $resolveMedia = function (?string $path, string $fallback): string {
        if (! empty($path)) {
            if (preg_match('#^(https?://|/)#', $path)) {
                return $path;
            }

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return \Illuminate\Support\Facades\Storage::url($path);
            }

            return asset($path);
        }

        return $fallback;
    };
@endphp

@section('title', $metaTitle ?? $post->title)

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="page-header__inner">
                <div class="page-header__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/page-header-bg.jpg') }});"></div>
                <div class="container">
                    <div class="page-header__content">
                        <h2 class="page-header__title">{{ $post->title }}</h2>
                        @if ($post->published_at)
                            <p>{{ $post->published_at->toFormattedDateString() }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <article class="blog-details section-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @if ($post->cover_image)
                        <div class="blog-details__image">
                            <img src="{{ $resolveMedia($post->cover_image, $themes->assetUrl('images/blog/blog-l-1-1.jpg')) }}" alt="{{ $post->title }}">
                        </div>
                    @endif
                    <div class="blog-details__content">
                        <div class="about-one__text">{!! nl2br(e($post->body)) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </article>
@endsection
