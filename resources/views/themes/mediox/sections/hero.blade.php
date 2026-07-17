@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $heroImage = ! empty($payload['image'])
        ? (preg_match('#^(https?://|/)#', $payload['image']) ? $payload['image'] : asset($payload['image']))
        : $themes->assetUrl('images/main-slider/main-slider-1-1.jpg');
    $bgImage = $themes->assetUrl('images/shapes/main-slider-bg-1-1.png');
@endphp
<section class="main-slider-one">
    <div class="main-slider-one__carousel mediox-owl__carousel mediox-owl__carousel--basic-nav owl-carousel owl-theme" data-owl-options='{"items": 1, "margin": 0, "loop": true, "smartSpeed": 700, "nav": false, "dots": false, "autoplay": true}'>
        <div class="main-slider-one__item">
            <div class="main-slider-one__bg" style="background-image: url({{ $bgImage }});"></div>
            <div class="container">
                <div class="row gutter-y-60">
                    <div class="col-xl-9">
                        <div class="main-slider-one__content">
                            @if (! empty($payload['subheading']))
                                <div class="main-slider-one__top">
                                    <p class="main-slider-one__sub-title">{{ $payload['subheading'] }}</p>
                                </div>
                            @endif
                            @if (! empty($payload['heading']))
                                <h2 class="main-slider-one__title">
                                    <span class="main-slider-one__title__inner">{{ $payload['heading'] }}</span>
                                </h2>
                            @endif
                            @if (! empty($payload['body']))
                                <div class="main-slider-one__description">
                                    <p class="main-slider-one__text">{{ $payload['body'] }}</p>
                                </div>
                            @endif
                            @if (! empty($payload['cta_label']))
                                <div class="main-slider-one__button">
                                    <div class="main-slider-one__button__inner">
                                        <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="main-slider-one__btn mediox-btn">
                                            <span>{{ $payload['cta_label'] }}</span>
                                            <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <img src="{{ $themes->assetUrl('images/shapes/main-slider-shape-1-2.png') }}" alt="" class="main-slider-one__content__shape-1">
                            <img src="{{ $themes->assetUrl('images/shapes/main-slider-shape-1-3.png') }}" alt="" class="main-slider-one__content__shape-2">
                        </div>
                    </div>
                    <div class="main-slider-one__image">
                        <img src="{{ $heroImage }}" alt="{{ $payload['heading'] ?? 'Hero' }}">
                    </div>
                </div>
            </div>
            <img src="{{ $themes->assetUrl('images/shapes/main-slider-shape-1-1.png') }}" alt="" class="main-slider-one__shape-1">
            <img src="{{ $themes->assetUrl('images/shapes/main-slider-shape-1-4.png') }}" alt="" class="main-slider-one__shape-2">
        </div>
    </div>
</section>
