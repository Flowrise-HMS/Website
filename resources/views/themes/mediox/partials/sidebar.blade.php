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
    $sidebarAbout = $branding->footerAboutText() ?? $siteName;
@endphp
<div class="sidebar-one__logo sidebar-one__item logo-retina">
    <a href="{{ route('website.home') }}" aria-label="logo image">
        <img src="{{ $branding->logoLightUrl() }}" style="max-height: 48px; width: auto; object-fit: contain;" alt="{{ $siteName }}" />
    </a>
</div>
<div class="sidebar-one__about sidebar-one__item">
    <p class="sidebar-one__about__text">{{ $sidebarAbout }}</p>
</div>
<div class="sidebar-one__info sidebar-one__item">
    <h4 class="sidebar-one__title">Contact</h4>
    <ul class="sidebar-one__info__list">
        @if ($contactEmail)
            <li>
                <span class="sidebar-one__info__icon">
                    <i class="icon-paper-plane"></i>
                </span>
                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
            </li>
        @endif
        @if ($contactPhone)
            <li>
                <span class="sidebar-one__info__icon">
                    <i class="icon-telephone"></i>
                </span>
                <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
            </li>
        @endif
    </ul>
</div>
