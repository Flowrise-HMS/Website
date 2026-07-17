@php
    try {
        $settings = \Modules\Website\Classes\Support\Website::settings();
        $siteName = $settings->meta_title ?: config('app.name');
        $contactEmail = $settings->contact_email;
        $contactPhone = $settings->contact_phone;
    } catch (\Throwable) {
        $siteName = config('app.name');
        $contactEmail = null;
        $contactPhone = null;
    }
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $branding = app(\Modules\Website\Classes\Support\ThemeBranding::class);
    $footerAbout = $branding->footerAboutText() ?? $siteName;
@endphp
<footer class="main-footer section-space-top">
    <div class="main-footer__bg" style="background-image: url({{ $themes->assetUrl('images/shapes/footer-bg.png') }});"></div>
    <div class="container">
        <div class="row gutter-y-40">
            <div class="col-xl-4 col-lg-6 col-md-7 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="00ms">
                <div class="footer-widget footer-widget--about">
                    <div class="footer-widget__logo logo-retina">
                        <a href="{{ route('website.home') }}">
                            <img src="{{ $branding->logoLightUrl() }}" alt="{{ $siteName }}" style="max-height: 48px; width: auto; object-fit: contain;">
                        </a>
                    </div>
                    <p class="footer-widget__about-text">{{ $footerAbout }}</p>
                    <a href="{{ route('website.booking') }}" class="footer-widget__btn">
                        <span>get consultant</span>
                        <span class="footer-widget__btn__icon"><i class="icon-up-right-arrow"></i></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="main-footer__bottom">
            <div class="main-footer__info">
                <div class="main-footer__info__bg" style="background-image: url({{ $themes->assetUrl('images/backgrounds/footer-contact-bg.jpg') }});"></div>
                <div class="row main-footer__info__row gutter-y-40">
                    @if ($contactEmail)
                        <div class="main-footer__info__col-2 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                            <div class="main-footer__contact">
                                <span class="main-footer__contact__icon">
                                    <i class="icon-email"></i>
                                </span>
                                <div class="main-footer__contact__content">
                                    <p class="main-footer__contact__title">send email</p>
                                    <h4 class="main-footer__contact__text">
                                        <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($contactPhone)
                        <div class="main-footer__info__col-3 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="200ms">
                            <div class="main-footer__contact">
                                <span class="main-footer__contact__icon">
                                    <i class="icon-telephone"></i>
                                </span>
                                <div class="main-footer__contact__content">
                                    <p class="main-footer__contact__title">call emergency</p>
                                    <h4 class="main-footer__contact__text">
                                        <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <p class="main-footer__copyright">
                &copy; Copyright <span class="dynamic-year"></span> {{ $siteName }}.
            </p>
        </div>
    </div>
</footer>
