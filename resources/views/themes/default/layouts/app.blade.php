<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $metaTitle ?? config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $metaDescription ?? '')">
    @stack('meta')
    <link rel="stylesheet" href="{{ asset('css/website/default-theme.css') }}">
</head>
<body class="website-body @if($animationsEnabled ?? true) website-animations @endif">
    @include('website::themes.default.partials.nav')
    <main id="main">
        @yield('content')
    </main>
    @include('website::themes.default.partials.footer')
    @livewireScripts
    <script>
        document.querySelectorAll('[data-reveal]').forEach((el) => {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                el.classList.add('is-visible');
                return;
            }
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            observer.observe(el);
        });
    </script>
    @stack('scripts')
</body>
</html>
