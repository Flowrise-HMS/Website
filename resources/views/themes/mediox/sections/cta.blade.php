@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp
<section class="appointment-one section-space-bottom">
    <div class="appointment-one__bg mediox-jarallax" data-jarallax data-speed="0.3s" style="background-image: url({{ $themes->assetUrl('images/backgrounds/appointment-bg.jpg') }});">
        <div class="appointment-one__bg__inner" style="background-image: url({{ $themes->assetUrl('images/shapes/appointment-shape-bg.png') }});"></div>
        <div class="appointment-one__bg__shape">
            <div class="appointment-one__bg__shape__1">
                <div class="appointment-one__bg__shape__2"></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 text-center">
                <div class="appointment-one__content">
                    @if (! empty($payload['heading']))
                        <h3 class="appointment-one__title">{{ $payload['heading'] }}</h3>
                    @endif
                    @if (! empty($payload['body']))
                        <p class="main-slider-one__text">{{ $payload['body'] }}</p>
                    @endif
                    @if (! empty($payload['cta_label']))
                        <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="mediox-btn">
                            <span>{{ $payload['cta_label'] }}</span>
                            <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <img src="{{ $themes->assetUrl('images/shapes/appointment-shape-1-1.png') }}" alt="" class="appointment-one__shape-1">
    <img src="{{ $themes->assetUrl('images/shapes/appointment-shape-1-2.png') }}" alt="" class="appointment-one__shape-2">
</section>
