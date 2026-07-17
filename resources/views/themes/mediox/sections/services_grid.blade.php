@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $items = $payload['items'] ?? [];
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
<section class="services-one section-space-two">
    <div class="services-one__bg" style="background-image: url({{ $themes->assetUrl('images/shapes/services-bg-1-1.png') }});"></div>
    <div class="container">
        @if (! empty($payload['heading']) || ! empty($payload['subheading']))
            <div class="sec-title sec-title--center wow fadeInUp" data-wow-duration="1500ms">
                <div class="sec-title__top">
                    <img src="{{ $themes->assetUrl('images/shapes/sec-title-s-1-2.png') }}" alt="" class="sec-title__img">
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
            <p class="about-one__text text-center mb-4">{{ $payload['body'] }}</p>
        @endif
        <div class="services-one__carousel mediox-owl__carousel mediox-owl__carousel--basic-nav owl-carousel owl-theme" data-owl-options='{"items": 1, "margin": 10, "loop": true, "smartSpeed": 700, "nav": false, "dots": true, "autoplay": true, "responsive": {"0": {"items": 1, "margin": 10}, "768": {"items": 2, "margin": 30}, "992": {"items": 3, "margin": 30}}}'>
            @forelse ($items as $index => $item)
                <div class="item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $index * 100 }}ms">
                    <div class="service-card">
                        <div class="service-card__image">
                            <img src="{{ $resolveMedia($item['image'] ?? null, $themes->assetUrl('images/services/service-1-1.jpg')) }}" alt="{{ $item['title'] ?? 'Service' }}">
                        </div>
                        @if (! empty($item['icon']))
                            <span class="service-card__icon"><i class="{{ $item['icon'] }}"></i></span>
                        @endif
                        <div class="service-card__content">
                            <div class="service-card__content__inner">
                                <h3 class="service-card__title">
                                    @if (! empty($item['url']))
                                        <a href="{{ $item['url'] }}">{{ $item['title'] ?? '' }}</a>
                                    @else
                                        {{ $item['title'] ?? '' }}
                                    @endif
                                </h3>
                                @if (! empty($item['url']))
                                    <a href="{{ $item['url'] }}" class="service-card__link">
                                        <i class="icon-up-right-arrow"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="item">
                    <div class="service-card">
                        <div class="service-card__content">
                            <div class="service-card__content__inner">
                                <p class="service-card__title">{{ $payload['body'] ?? 'Services coming soon.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        @if (! empty($payload['cta_label']))
            <div class="text-center mt-4">
                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="mediox-btn">
                    <span>{{ $payload['cta_label'] }}</span>
                    <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                </a>
            </div>
        @endif
    </div>
</section>
