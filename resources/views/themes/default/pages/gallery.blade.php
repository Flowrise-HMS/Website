@extends('website::themes.default.layouts.app')
@section('title', $metaTitle ?? 'Gallery')
@section('content')
<section class="website-section" data-reveal>
    <div class="website-container">
        <h1 class="website-section__title">Gallery</h1>
        @foreach ($albums as $album)
            <div class="website-card">
                <h2>{{ $album->title }}</h2>
                <p>{{ $album->description }}</p>
                <p>{{ $album->items->count() }} photos</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
