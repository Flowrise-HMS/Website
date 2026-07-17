@extends('website::themes.clinicalmaster.layouts.app')

@php
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
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => 'Partners'])

    <section class="py-15 lg:py-20">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 items-center">
                @foreach ($partners as $partner)
                    @php $logoUrl = $resolveMedia($partner->logo); @endphp
                    <div class="flex items-center justify-center p-4 bg-white rounded-lg shadow-sm">
                        @if ($logoUrl)
                            @if ($partner->url)
                                <a href="{{ $partner->url }}" rel="noopener" target="_blank">
                                    <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="max-h-16 w-auto object-contain">
                                </a>
                            @else
                                <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="max-h-16 w-auto object-contain">
                            @endif
                        @else
                            <span class="text-secondary font-medium text-center">{{ $partner->name }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
