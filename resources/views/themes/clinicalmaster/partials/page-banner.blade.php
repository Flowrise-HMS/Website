@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $bannerTitle = $title ?? config('app.name');
@endphp
<section class="cm-page-banner">
    <div
        class="cm-page-banner__media"
        style="background-image: url({{ $themes->assetUrl('images/hero-banner/banner-1.jpg') }});"
        aria-hidden="true"
    ></div>
    <div class="cm-page-banner__overlay" aria-hidden="true"></div>
    <div class="container cm-page-banner__content">
        <h1 class="cm-page-banner__title">{{ $bannerTitle }}</h1>
        <nav class="cm-page-banner__breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('website.home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span>{{ $bannerTitle }}</span>
        </nav>
    </div>
</section>
