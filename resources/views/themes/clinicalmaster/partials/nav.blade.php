@php
    $menu = \Modules\Website\Models\Menu::query()
        ->where('location', 'primary')
        ->with(['items' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order')->with('page')])
        ->first();

    try {
        $settings = \Modules\Website\Classes\Support\Website::settings();
        $siteName = $settings->meta_title ?: config('app.name');
    } catch (\Throwable) {
        $siteName = config('app.name');
    }

    $branding = app(\Modules\Website\Classes\Support\ThemeBranding::class);
    $bookingUrl = route('website.booking');

    $navItems = collect($menu?->items ?? [])
        ->map(function ($item) {
            $href = match ($item->type->value) {
                'page', 'cta' => $item->page ? route('website.page', $item->page->slug) : '#',
                default => $item->url ?? '#',
            };

            return [
                'label' => $item->label,
                'href' => $href,
                'new_tab' => (bool) $item->open_in_new_tab,
            ];
        })
        ->reject(function (array $item) use ($bookingUrl): bool {
            // Booking is always the dedicated header CTA — keep it out of the link list.
            $href = strtolower(rtrim(parse_url($item['href'], PHP_URL_PATH) ?? $item['href'], '/'));
            $bookingPath = strtolower(rtrim(parse_url($bookingUrl, PHP_URL_PATH) ?? $bookingUrl, '/'));
            $label = strtolower($item['label']);

            return $href === $bookingPath
                || str_contains($href, 'book-appointment')
                || str_contains($label, 'book appointment')
                || $label === 'book'
                || $label === 'appointment';
        })
        ->values();

    if ($navItems->isEmpty()) {
        $navItems = collect([
            ['label' => 'Home', 'href' => route('website.home'), 'new_tab' => false],
            ['label' => 'About', 'href' => route('website.page', 'about'), 'new_tab' => false],
            ['label' => 'Services', 'href' => route('website.page', 'services'), 'new_tab' => false],
            ['label' => 'Contact', 'href' => route('website.page', 'contact'), 'new_tab' => false],
        ]);
    }
@endphp
<header class="cm-site-header" id="cmSiteHeader">
    <div class="cm-main-bar">
        <div class="container">
            <div class="cm-header-inner">
                <a href="{{ route('website.home') }}" class="cm-logo-link">
                    <img
                        src="{{ $branding->logoLightUrl() }}"
                        alt="{{ $siteName }}"
                        class="cm-logo cm-logo-light"
                        style="max-height: 40px; width: auto; object-fit: contain;"
                    >
                    <img
                        src="{{ $branding->logoDarkUrl() }}"
                        alt=""
                        class="cm-logo cm-logo-dark"
                        style="max-height: 40px; width: auto; object-fit: contain;"
                        aria-hidden="true"
                    >
                </a>

                <div class="cm-header-actions">
                    <nav class="cm-desktop-nav" aria-label="Primary">
                        <ul class="cm-nav-list">
                            @foreach ($navItems as $item)
                                <li>
                                    <a href="{{ $item['href'] }}" @if($item['new_tab']) target="_blank" rel="noopener" @endif>
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>

                    <a href="{{ $bookingUrl }}" class="btn btn-primary btn-sm cm-book-btn">
                        Book Appointment
                    </a>

                    <button
                        type="button"
                        class="cm-menu-toggle"
                        id="cmMenuToggle"
                        aria-expanded="false"
                        aria-controls="cmMobileNav"
                        aria-label="Toggle navigation"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="cm-mobile-nav" id="cmMobileNav" hidden>
            <div class="container">
                <ul class="cm-nav-list">
                    @foreach ($navItems as $item)
                        <li>
                            <a href="{{ $item['href'] }}" @if($item['new_tab']) target="_blank" rel="noopener" @endif>
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ $bookingUrl }}" class="btn btn-primary btn-sm cm-mobile-book">
                    Book Appointment
                </a>
            </div>
        </div>
    </div>
</header>
