@php
    $menu = \Modules\Website\Models\Menu::query()->where('location', 'primary')->with(['items' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order')->with('page')])->first();
    try {
        $settings = \Modules\Website\Classes\Support\Website::settings();
        $siteName = $settings->meta_title ?: config('app.name');
        $contactPhone = $settings->contact_phone;
    } catch (\Throwable) {
        $siteName = config('app.name');
        $contactPhone = null;
    }
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $branding = app(\Modules\Website\Classes\Support\ThemeBranding::class);
@endphp
<header class="main-header main-header--two sticky-header sticky-header--normal">
    <div class="container-fluid">
        <div class="main-header__inner">
            <div class="main-header__logo logo-retina">
                <a href="{{ route('website.home') }}">
                    <img src="{{ $branding->logoDarkUrl() }}" alt="{{ $siteName }}" style="max-height: 48px; width: auto; object-fit: contain;">
                </a>
            </div>
            <div class="main-header__right">
                <div class="main-header__sidebar-btn sidebar-btn__toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <nav class="main-header__nav main-menu">
                    <ul class="main-menu__list">
                        @forelse ($menu?->items ?? [] as $item)
                            @php
                                $href = match ($item->type->value) {
                                    'page', 'cta' => $item->page ? route('website.page', $item->page->slug) : '#',
                                    default => $item->url ?? '#',
                                };
                            @endphp
                            <li>
                                <a href="{{ $href }}" @if($item->open_in_new_tab) target="_blank" rel="noopener" @endif>{{ $item->label }}</a>
                            </li>
                        @empty
                            <li><a href="{{ route('website.home') }}">Home</a></li>
                            <li><a href="{{ route('website.booking') }}">Book Appointment</a></li>
                        @endforelse
                    </ul>
                </nav>
                <div class="mobile-nav__btn mobile-nav__toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <a href="#" class="search-toggler main-header__search">
                    <i class="icon-search" aria-hidden="true"></i>
                    <span class="sr-only">Search</span>
                </a>
                @if ($contactPhone)
                    <div class="main-header__call">
                        <span class="main-header__call__icon">
                            <i class="icon-telephone"></i>
                        </span>
                        <div class="main-header__call__content">
                            <p class="main-header__call__title">call emergency</p>
                            <h4 class="main-header__call__number">
                                <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                            </h4>
                        </div>
                    </div>
                @endif
                <a href="{{ route('website.booking') }}" class="mediox-btn main-header__btn">
                    <span>make an appointment</span>
                    <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                </a>
            </div>
        </div>
    </div>
</header>
