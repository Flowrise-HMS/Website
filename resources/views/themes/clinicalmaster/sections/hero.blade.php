@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $heroImage = ! empty($payload['image'])
        ? (preg_match('#^(https?://|/)#', $payload['image']) ? $payload['image'] : asset($payload['image']))
        : $themes->assetUrl('images/hero-banner/banner-1.jpg');
@endphp
<section class="cm-hero">
    <div class="cm-hero__media">
        <img src="{{ $heroImage }}" alt="{{ $payload['heading'] ?? 'Hero' }}">
    </div>
    <div class="cm-hero__overlay" aria-hidden="true"></div>
    <div class="container cm-hero__content">
        <div class="cm-hero__inner">
            @if (! empty($payload['subheading']))
                <span class="cm-hero__eyebrow">{{ $payload['subheading'] }}</span>
            @endif
            @if (! empty($payload['heading']))
                <h1 class="cm-hero__title">{{ $payload['heading'] }}</h1>
            @endif
            @if (! empty($payload['body']))
                <p class="cm-hero__body">{{ $payload['body'] }}</p>
            @endif
            @if (! empty($payload['cta_label']))
                <div class="cm-hero__cta">
                    <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="btn btn-primary btn-lg">
                        {{ $payload['cta_label'] }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
