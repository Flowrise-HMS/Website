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
            .btn-primary {
                background-color: var(--website-brand-primary, unset);
                border-color: var(--website-brand-primary, unset);
            }
            .btn-primary:hover {
                background-color: var(--website-brand-secondary, var(--website-brand-primary, unset));
                border-color: var(--website-brand-secondary, var(--website-brand-primary, unset));
            }
        </style>
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $themes->assetUrl('icons/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('icons/flaticon/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('icons/feather/css/iconfont.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('vendor/swiper/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('css/style.css') }}" />
    <link rel="stylesheet" href="{{ $themes->assetUrl('css/app-overrides.css') }}" />
</head>
<body id="bg" class="cm-no-smoother" data-typography="typography_1" data-theme-color="skin-10">
    <div class="page-wraper">
        @include('website::themes.clinicalmaster.partials.nav')

        <main id="main">
            @yield('content')
        </main>

        @include('website::themes.clinicalmaster.partials.footer')

        <a href="#main" class="cm-scroll-top" id="cmScrollTop" aria-label="Back to top">
            <i class="feather icon-arrow-up" aria-hidden="true"></i>
        </a>
    </div>

    @include('website::themes.clinicalmaster.partials.scripts')
    @livewireScripts
    @stack('scripts')
</body>
</html>
