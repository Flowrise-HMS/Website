@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp
<section class="py-15 lg:py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-secondary"></div>
    <div class="absolute inset-0 opacity-20 bg-cover bg-center" style="background-image: url({{ $themes->assetUrl('images/background/bg04.webp') }});"></div>
    <div class="container relative z-1">
        <div class="max-w-3xl mx-auto text-center text-white">
            @if (! empty($payload['heading']))
                <h2 class="text-3xl md:text-4xl font-semibold mb-4">{{ $payload['heading'] }}</h2>
            @endif
            @if (! empty($payload['body']))
                <p class="text-white/80 text-lg mb-8">{{ $payload['body'] }}</p>
            @endif
            @if (! empty($payload['cta_label']))
                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="btn btn-primary btn-lg">
                    {{ $payload['cta_label'] }}
                </a>
            @endif
        </div>
    </div>
</section>
