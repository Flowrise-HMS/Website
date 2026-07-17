@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp
<section class="about-one section-space">
    <div class="container">
        <div class="row gutter-y-60 align-items-center">
            <div class="col-lg-12">
                <div class="about-one__content">
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
                        <div class="about-one__text-box wow fadeInUp" data-wow-duration="1500ms">
                            <p class="about-one__text">{!! nl2br(e($payload['body'])) !!}</p>
                        </div>
                    @endif
                    @if (! empty($payload['cta_label']))
                        <div class="about-one__bottom">
                            <div class="about-one__button wow fadeInUp" data-wow-duration="1500ms">
                                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="mediox-btn">
                                    <span>{{ $payload['cta_label'] }}</span>
                                    <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
