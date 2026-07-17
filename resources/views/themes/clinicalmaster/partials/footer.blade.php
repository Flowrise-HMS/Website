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
    $branding = app(\Modules\Website\Classes\Support\ThemeBranding::class);
    $footerAbout = $branding->footerAboutText() ?? $siteName;
@endphp
<footer class="cm-footer site-footer">
    <div class="container">
        <div class="cm-footer-grid">
            <div>
                <div class="cm-footer-logo" style="margin-bottom: 1.25rem;">
                    <a href="{{ route('website.home') }}">
                        <img src="{{ $branding->logoLightUrl() }}" alt="{{ $siteName }}" style="max-height: 40px; width: auto; object-fit: contain;">
                    </a>
                </div>
                <p style="line-height: 1.6; margin-bottom: 1.25rem;">{{ $footerAbout }}</p>
                <a href="{{ route('website.booking') }}" class="btn btn-primary btn-sm">Book Appointment</a>
            </div>
            <div>
                <h5>Contact</h5>
                <ul class="cm-footer-contact">
                    @if ($contactPhone)
                        <li>
                            <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                        </li>
                    @endif
                    @if ($contactEmail)
                        <li>
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="cm-footer-bottom">
            <p>&copy; <span class="current-year"></span> {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
