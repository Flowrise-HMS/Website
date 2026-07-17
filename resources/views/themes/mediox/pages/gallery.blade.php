@extends('website::themes.mediox.layouts.app')

@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $resolveMedia = fn (?string $path, string $fallback): string => \Modules\Website\Classes\Support\SafeUrl::media($path, $fallback);
@endphp

@section('title', $metaTitle ?? 'Gallery')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="page-header__inner">
                <div class="page-header__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/page-header-bg.jpg') }});"></div>
                <div class="container">
                    <div class="page-header__content">
                        <h2 class="page-header__title">Gallery</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-page section-space">
        <div class="container">
            @foreach ($albums as $album)
                <div class="sec-title wow fadeInUp" data-wow-duration="1500ms">
                    <h3 class="sec-title__title">{{ $album->title }}</h3>
                    @if ($album->description)
                        <p class="about-one__text">{{ $album->description }}</p>
                    @endif
                </div>
                <div class="row gutter-y-30">
                    @forelse ($album->items as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="gallery-page__card">
                                <img src="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/gallery/gallery-1-3.jpg')) }}" alt="{{ $item->alt_text ?: $item->title ?: $album->title }}">
                                <div class="gallery-page__card__hover">
                                    <a href="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/gallery/gallery-1-3.jpg')) }}" class="img-popup">
                                        <span class="gallery-page__card__icon"></span>
                                    </a>
                                    <span class="gallery-page__card__hover__box gallery-page__card__hover__box--1"></span>
                                    <span class="gallery-page__card__hover__box gallery-page__card__hover__box--2"></span>
                                    <span class="gallery-page__card__hover__box gallery-page__card__hover__box--3"></span>
                                    <span class="gallery-page__card__hover__box gallery-page__card__hover__box--4"></span>
                                </div>
                            </div>
                        </div>
                    @empty
                        @if ($album->cover_image)
                            <div class="col-md-6 col-lg-4">
                                <div class="gallery-page__card">
                                    <img src="{{ $resolveMedia($album->cover_image, $themes->assetUrl('images/gallery/gallery-1-3.jpg')) }}" alt="{{ $album->title }}">
                                </div>
                            </div>
                        @endif
                    @endforelse
                </div>
            @endforeach
        </div>
    </section>
@endsection
