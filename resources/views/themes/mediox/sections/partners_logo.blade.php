@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $partners = \Modules\Website\Models\Partner::query()->where('is_published', true)->orderBy('sort_order')->get();
    $resolveMedia = function (?string $path): ?string {
        if (empty($path)) {
            return null;
        }
        if (preg_match('#^(https?://|/)#', $path)) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($path)
            ? \Illuminate\Support\Facades\Storage::url($path)
            : asset($path);
    };
@endphp
@if (! empty($payload['heading']) || ! empty($payload['body']))
    <section class="section-space-top">
        <div class="container">
            @if (! empty($payload['heading']))
                <div class="sec-title sec-title--center">
                    <h3 class="sec-title__title">{{ $payload['heading'] }}</h3>
                </div>
            @endif
            @if (! empty($payload['body']))
                <p class="about-one__text text-center">{{ $payload['body'] }}</p>
            @endif
        </div>
    </section>
@endif
<div class="client-carousel section-space-bottom">
    <div class="container">
        <div class="client-carousel__one mediox-owl__carousel owl-theme owl-carousel" data-owl-options='{"items": 5, "margin": 65, "loop": true, "autoplay": true, "nav": false, "dots": false, "responsive": {"0": {"items": 2, "margin": 30}, "768": {"items": 4}, "992": {"items": 5}}}'>
            @foreach ($partners as $partner)
                <div class="client-carousel__one__item">
                    @php $logoUrl = $resolveMedia($partner->logo); @endphp
                    @if ($logoUrl)
                        @if ($partner->url)
                            <a href="{{ $partner->url }}" rel="noopener" target="_blank">
                                <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="client-carousel__one__image">
                            </a>
                        @else
                            <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="client-carousel__one__image">
                        @endif
                    @else
                        <span class="client-carousel__one__image">{{ $partner->name }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
