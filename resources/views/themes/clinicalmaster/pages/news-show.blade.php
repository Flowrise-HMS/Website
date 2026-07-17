@extends('website::themes.clinicalmaster.layouts.app')

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
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => $post->title])

    <article class="py-15 lg:py-20">
        <div class="container">
            <div class="max-w-3xl mx-auto">
                @if ($post->published_at)
                    <time class="text-sm text-primary font-medium">{{ $post->published_at->toFormattedDateString() }}</time>
                @endif
                @if ($post->cover_image)
                    <div class="mt-6 mb-8 rounded-xl overflow-hidden shadow-lg">
                        <img src="{{ $resolveMedia($post->cover_image, $themes->assetUrl('images/blog/img1.webp')) }}" alt="{{ $post->title }}" class="w-full">
                    </div>
                @endif
                <div class="prose max-w-none text-bodycolor leading-relaxed">
                    {!! nl2br(e($post->body)) !!}
                </div>
            </div>
        </div>
    </article>
@endsection
