@extends('website::themes.clinicalmaster.layouts.app')

@php
    $metaTitle = $page->meta_title ?: $page->title;
    $metaDescription = $page->meta_description;
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription ?? '')

@section('content')
    @include('website::themes.clinicalmaster.partials.page-banner', ['title' => $page->title ?: 'FAQs'])
    @include('website::themes.clinicalmaster.partials.page-sections')
@endsection
