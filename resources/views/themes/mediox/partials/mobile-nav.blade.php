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
@endphp
<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="icon-close"></i></span>

        <div class="logo-box logo-retina">
            <a href="{{ route('website.home') }}" aria-label="logo image">
                <img src="{{ $branding->logoLightUrl() }}" style="max-height: 48px; width: auto; object-fit: contain;" alt="{{ $siteName }}" />
            </a>
        </div>

        <div class="mobile-nav__container"></div>

        <ul class="mobile-nav__contact list-unstyled">
            @if ($contactEmail)
                <li>
                    <span class="mobile-nav__contact__icon">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                </li>
            @endif
            @if ($contactPhone)
                <li>
                    <span class="mobile-nav__contact__icon">
                        <i class="fa fa-phone-alt"></i>
                    </span>
                    <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                </li>
            @endif
        </ul>
    </div>
</div>
