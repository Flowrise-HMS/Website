<section class="py-15 lg:py-20">
    <div class="container">
        <div class="max-w-4xl mx-auto text-center lg:text-left">
            @if (! empty($payload['heading']) || ! empty($payload['subheading']))
                <div class="mb-8">
                    @if (! empty($payload['subheading']))
                        <span class="inline-block text-primary font-medium mb-3">{{ $payload['subheading'] }}</span>
                    @endif
                    @if (! empty($payload['heading']))
                        <h2 class="text-3xl md:text-4xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
                    @endif
                </div>
            @endif
            @if (! empty($payload['body']))
                <div class="text-bodycolor leading-relaxed text-lg">
                    <p>{!! nl2br(e($payload['body'])) !!}</p>
                </div>
            @endif
            @if (! empty($payload['cta_label']))
                <div class="mt-8">
                    <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="btn btn-primary">
                        {{ $payload['cta_label'] }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
