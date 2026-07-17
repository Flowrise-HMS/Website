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

@section('title', $metaTitle ?? 'News')

@section('content')
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => 'News'])

    <section class="py-15 lg:py-20">
        <div class="container">
            <div class="max-w-4xl mx-auto space-y-10">
                @forelse ($posts as $post)
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden md:flex">
                        <div class="md:w-2/5 shrink-0">
                            <img src="{{ $resolveMedia($post->cover_image, $themes->assetUrl('images/blog/img1.webp')) }}" alt="{{ $post->title }}" class="w-full h-full min-h-[220px] object-cover">
                        </div>
                        <div class="p-6 md:p-8 flex flex-col justify-center">
                            @if ($post->published_at)
                                <time class="text-sm text-primary font-medium">{{ $post->published_at->toFormattedDateString() }}</time>
                            @endif
                            <h2 class="text-xl md:text-2xl font-semibold text-secondary mt-2 mb-3">
                                <a href="{{ route('website.news.show', $post->slug) }}" class="hover:text-primary">{{ $post->title }}</a>
                            </h2>
                            @if ($post->excerpt)
                                <p class="text-bodycolor mb-4">{{ $post->excerpt }}</p>
                            @endif
                            <a href="{{ route('website.news.show', $post->slug) }}" class="btn btn-primary btn-sm self-start">Read More</a>
                        </div>
                    </article>
                @empty
                    <p class="text-bodycolor text-center">No news yet.</p>
                @endforelse
            </div>
            <div class="mt-10">{{ $posts->links() }}</div>
        </div>
    </section>
@endsection
