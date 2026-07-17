@extends('website::themes.default.layouts.app')
@section('title', $metaTitle ?? $post->title)
@section('content')
<article class="website-section" data-reveal>
    <div class="website-container">
        <h1 class="website-section__title">{{ $post->title }}</h1>
        <p class="website-section__sub">{{ optional($post->published_at)->toFormattedDateString() }}</p>
        <div class="website-prose">{!! nl2br(e($post->body)) !!}</div>
    </div>
</article>
@endsection
