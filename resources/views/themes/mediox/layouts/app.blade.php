@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $branding = app(\Modules\Website\Classes\Support\ThemeBranding::class);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', $metaTitle ?? config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $metaDescription ?? '')" />
    @stack('meta')
    <link rel="icon" type="image/png" href="{{ $branding->faviconUrl() }}" />
    @if ($brandStyle = $branding->cssVariablesStyle())
        <style>
            {!! $brandStyle !!}
            .mediox-btn,
            .main-slider-one__btn,
            .footer-widget__btn {
                background-color: var(--website-brand-primary, unset);
            }
            .mediox-btn:hover,
            .main-slider-one__btn:hover,
            .footer-widget__btn:hover {
                background-color: var(--website-brand-secondary, var(--website-brand-primary, unset));
            }
        </style>
    @endif
    <link rel="manifest" href="{{ $themes->assetUrl('images/favicons/site.webmanifest') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/bootstrap-select/bootstrap-select.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/animate/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/jquery-ui/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/jarallax/jarallax.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/jquery-magnific-popup/jquery.magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/nouislider/nouislider.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/nouislider/nouislider.pips.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/tiny-slider/tiny-slider.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/mediox-icons/style.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/owl-carousel/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/owl-carousel/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendors/slick/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('css/mediox.css') }}" />
</head>
<body class="custom-cursor">
    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>

    <div class="preloader">
        <div class="preloader__image" style="background-image: url({{ $themes->assetUrl('images/loader.png') }});"></div>
    </div>

    <div class="page-wrapper">
        @include('website::themes.mediox.partials.nav')

        <main id="main">
            @yield('content')
        </main>

        @include('website::themes.mediox.partials.footer')
    </div>

    @include('website::themes.mediox.partials.mobile-nav')

    <div class="search-popup">
        <div class="search-popup__overlay search-toggler"></div>
        <div class="search-popup__content">
            <form role="search" method="get" class="search-popup__form" action="#">
                <input type="text" id="search" placeholder="Search Here..." />
                <button type="submit" aria-label="search submit" class="mediox-btn">
                    <i class="icon-search"></i>
                </button>
            </form>
        </div>
    </div>

    <aside class="sidebar-one">
        <div class="sidebar-one__overlay sidebar-btn__toggler"></div>
        <div class="sidebar-one__content">
            <span class="sidebar-one__close sidebar-btn__toggler"><i class="icon-close"></i></span>
            @include('website::themes.mediox.partials.sidebar')
        </div>
    </aside>

    <a href="#" data-target="html" class="scroll-to-target scroll-to-top">
        <span class="scroll-to-top__text">back top</span>
        <span class="scroll-to-top__wrapper"><span class="scroll-to-top__inner"></span></span>
    </a>

    @include('website::themes.mediox.partials.scripts')
    @livewireScripts
    @stack('scripts')
</body>
</html>
