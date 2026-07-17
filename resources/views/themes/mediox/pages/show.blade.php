@extends('website::themes.mediox.layouts.app')

@php
    $metaTitle = $page->meta_title ?: $page->title;
    $metaDescription = $page->meta_description;
    try { $animationsEnabled = \Modules\Website\Classes\Support\Website::settings()->animations_enabled; } catch (Throwable $e) { $animationsEnabled = true; }
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription ?? '')

@section('content')
    @if (! empty($jsonLd))
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @foreach ($sections as $section)
        @include($themes->sectionViewName($section->type->value), ['section' => $section, 'payload' => $section->payload ?? []])
    @endforeach
@endsection
