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

@section('title', $metaTitle ?? 'Team')

@section('content')
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => 'Our Team'])

    <section class="py-15 lg:py-20">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($members as $member)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg group">
                        <div class="aspect-[4/5] overflow-hidden">
                            <img src="{{ $resolveMedia($member->photo, $themes->assetUrl('images/post/img1.webp')) }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-secondary">{{ $member->name }}</h3>
                            <p class="text-primary font-medium mt-1">{{ $member->role }}</p>
                            @if ($member->bio)
                                <p class="text-bodycolor mt-3 text-sm leading-relaxed">{{ $member->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
