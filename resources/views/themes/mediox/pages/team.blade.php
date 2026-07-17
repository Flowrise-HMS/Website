@extends('website::themes.mediox.layouts.app')

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
    <section class="page-header">
        <div class="container">
            <div class="page-header__inner">
                <div class="page-header__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/page-header-bg.jpg') }});"></div>
                <div class="container">
                    <div class="page-header__content">
                        <h2 class="page-header__title">Our Team</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-page section-space">
        <div class="container">
            <div class="row gutter-y-30">
                @foreach ($members as $member)
                    <div class="col-lg-4 col-md-6">
                        <div class="team-card-three wow fadeInUp" data-wow-duration="1500ms">
                            <div class="team-card-three__image" style="background-image: url({{ $resolveMedia($member->photo, $themes->assetUrl('images/team/team-3-1.jpg')) }})"></div>
                            <div class="team-card-three__hover">
                                <div class="team-card-three__identity">
                                    <h3 class="team-card-three__name">{{ $member->name }}</h3>
                                    <p class="team-card-three__designation">{{ $member->role }}</p>
                                    @if ($member->bio)
                                        <p class="team-card-three__designation">{{ $member->bio }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
