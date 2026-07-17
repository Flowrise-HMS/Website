@extends('website::themes.default.layouts.app')
@section('title', $metaTitle ?? 'Team')
@section('content')
<section class="website-section" data-reveal>
    <div class="website-container">
        <h1 class="website-section__title">Our Team</h1>
        @foreach ($members as $member)
            <article class="website-card"><h2>{{ $member->name }}</h2><p>{{ $member->role }}</p><p>{{ $member->bio }}</p></article>
        @endforeach
    </div>
</section>
@endsection
