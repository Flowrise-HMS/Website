<section class="website-section website-section--hero website-section--hero-alt" data-reveal>
    <div class="website-container">
        <p class="website-eyebrow">Care you can trust</p>
        <h1 class="website-section__title">{{ $payload['heading'] ?? 'Welcome' }}</h1>
        @if(!empty($payload['subheading']))<p class="website-section__sub">{{ $payload['subheading'] }}</p>@endif
        @if(!empty($payload['cta_label']))<p><a class="website-btn" href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}">{{ $payload['cta_label'] }}</a></p>@endif
    </div>
</section>
