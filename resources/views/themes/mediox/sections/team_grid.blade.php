@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $members = \Modules\Website\Models\TeamMember::query()->where('is_published', true)->orderBy('sort_order')->get();
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
<section class="team-one">
    <div class="team-one__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/team-bg-1-1.jpg') }});"></div>
    <div class="container">
        <div class="row gutter-y-50">
            <div class="col-lg-6">
                @if (! empty($payload['heading']) || ! empty($payload['subheading']))
                    <div class="sec-title wow fadeInUp" data-wow-duration="1500ms">
                        <div class="sec-title__top">
                            <img src="{{ $themes->assetUrl('images/shapes/sec-title-s-1-1.png') }}" alt="" class="sec-title__img">
                            @if (! empty($payload['subheading']))
                                <h6 class="sec-title__tagline">{{ $payload['subheading'] }}</h6>
                            @endif
                        </div>
                        @if (! empty($payload['heading']))
                            <h3 class="sec-title__title">{{ $payload['heading'] }}</h3>
                        @endif
                    </div>
                @endif
                @if (! empty($payload['body']))
                    <p class="about-one__text">{{ $payload['body'] }}</p>
                @endif
                @if (! empty($payload['cta_label']))
                    <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null, route('website.team')) }}" class="mediox-btn">
                        <span>{{ $payload['cta_label'] }}</span>
                        <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                    </a>
                @endif
            </div>
            <div class="col-lg-6 order-1 order-lg-0">
                <div class="team-one__carousel mediox-owl__carousel mediox-owl__carousel--basic-nav owl-carousel owl-theme" data-owl-options='{"items": 1, "margin": 10, "loop": true, "smartSpeed": 700, "nav": true, "dots": false, "autoplay": true, "responsive": {"0": {"items": 1}, "768": {"items": 2, "margin": 30}}}'>
                    @foreach ($members as $index => $member)
                        <div class="item">
                            <div class="team-card wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $index * 100 }}ms">
                                <img src="{{ $resolveMedia($member->photo, $themes->assetUrl('images/team/team-1-1.jpg')) }}" alt="{{ $member->name }}" class="team-card__image">
                                <div class="team-card__identity">
                                    <h3 class="team-card__name">{{ $member->name }}</h3>
                                    <p class="team-card__designation">{{ $member->role }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
