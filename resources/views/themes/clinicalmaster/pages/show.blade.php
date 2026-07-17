@extends('website::themes.clinicalmaster.layouts.app')

@php
    $metaTitle = $page->meta_title ?: $page->title;
    $metaDescription = $page->meta_description;
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription ?? '')

@section('content')
    @if (! empty($jsonLd))
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => $page->title])
    @include('website::themes.clinicalmaster.partials.page-sections')
@endsection
