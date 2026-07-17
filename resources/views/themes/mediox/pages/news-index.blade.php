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

@section('title', $metaTitle ?? 'News')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="page-header__inner">
                <div class="page-header__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/page-header-bg.jpg') }});"></div>
                <div class="container">
                    <div class="page-header__content">
                        <h2 class="page-header__title">News</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-page blog-page--list section-space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row gutter-y-40">
                        @forelse ($posts as $post)
                            <div class="col-md-12">
                                <div class="blog-card-four wow fadeInUp" data-wow-duration="1500ms">
                                    <div class="blog-card-four__image">
                                        <img src="{{ $resolveMedia($post->cover_image, $themes->assetUrl('images/blog/blog-l-1-1.jpg')) }}" alt="{{ $post->title }}">
                                        <a href="{{ route('website.news.show', $post->slug) }}" class="blog-card-four__image__link">
                                            <span class="sr-only">{{ $post->title }}</span>
                                        </a>
                                        @if ($post->published_at)
                                            <div class="blog-card-four__date">
                                                <span class="blog-card-four__date__day">{{ $post->published_at->format('d') }}</span>
                                                <span class="blog-card-four__date__month">{{ strtolower($post->published_at->format('M')) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="blog-card-four__content">
                                        <h3 class="blog-card-four__title">
                                            <a href="{{ route('website.news.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                        @if ($post->excerpt)
                                            <p class="blog-card-four__text">{{ $post->excerpt }}</p>
                                        @endif
                                        <a href="{{ route('website.news.show', $post->slug) }}" class="blog-card-four__btn mediox-btn">
                                            <span>read more</span>
                                            <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <p>No news yet.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-4">{{ $posts->links() }}</div>
                </div>
            </div>
        </div>
    </section>
@endsection
