@extends('website::themes.default.layouts.app')
@section('title', $metaTitle ?? 'Partners')
@section('content')
<section class="website-section" data-reveal>
    <div class="website-container">
        <h1 class="website-section__title">Partners</h1>
        @foreach ($partners as $partner)
            <div class="website-partner">@if($partner->url)<a href="{{ $partner->url }}" rel="noopener">{{ $partner->name }}</a>@else{{ $partner->name }}@endif</div>
        @endforeach
    </div>
</section>
@endsection
