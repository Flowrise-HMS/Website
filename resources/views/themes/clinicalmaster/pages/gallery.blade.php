@extends('website::themes.clinicalmaster.layouts.app')

@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $resolveMedia = fn (?string $path, string $fallback): string => \Modules\Website\Classes\Support\SafeUrl::media($path, $fallback);
@endphp

@section('title', $metaTitle ?? 'Gallery')

@section('content')
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => 'Gallery'])

    <section class="py-15 lg:py-20">
        <div class="container">
            @foreach ($albums as $album)
                <div class="mb-10">
                    <h2 class="text-2xl font-semibold text-secondary mb-2">{{ $album->title }}</h2>
                    @if ($album->description)
                        <p class="text-bodycolor mb-6">{{ $album->description }}</p>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @forelse ($album->items as $item)
                        <div class="rounded-xl overflow-hidden shadow-md group relative">
                            <img src="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/post/img1.webp')) }}" alt="{{ $item->alt_text ?: $item->title ?: $album->title }}" class="w-full aspect-[4/3] object-cover">
                            <a href="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/post/img1.webp')) }}" class="absolute inset-0 bg-secondary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                <i class="feather icon-maximize text-2xl"></i>
                            </a>
                        </div>
                    @empty
                        @if ($album->cover_image)
                            <div class="rounded-xl overflow-hidden shadow-md">
                                <img src="{{ $resolveMedia($album->cover_image, $themes->assetUrl('images/post/img1.webp')) }}" alt="{{ $album->title }}" class="w-full aspect-[4/3] object-cover">
                            </div>
                        @endif
                    @endforelse
                </div>
            @endforeach
        </div>
    </section>
@endsection
