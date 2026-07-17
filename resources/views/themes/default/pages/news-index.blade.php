@extends('website::themes.default.layouts.app')
@section('title', $metaTitle ?? 'News')
@section('content')
<section class="website-section" data-reveal>
    <div class="website-container">
        <h1 class="website-section__title">News</h1>
        @forelse ($posts as $post)
            <article class="website-card">
                <h2><a href="{{ route('website.news.show', $post->slug) }}">{{ $post->title }}</a></h2>
                <p>{{ $post->excerpt }}</p>
            </article>
        @empty
            <p>No news yet.</p>
        @endforelse
        <div style="margin-top:1rem">{{ $posts->links() }}</div>
    </div>
</section>
@endsection
