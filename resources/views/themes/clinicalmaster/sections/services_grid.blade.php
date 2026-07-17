@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $items = $payload['items'] ?? [];
    $resolveMedia = function (?string $path, string $fallback): string {
        if (! empty($path)) {
            if (preg_match('#^(https?://|/)#', $path)) {
                return $path;
            }

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return \Illuminate\Support\Facades\Storage::url($path);
            }

            return asset($path);
        }

        return $fallback;
    };
@endphp
<section class="py-15 lg:py-20">
    <div class="container">
        @if (! empty($payload['heading']) || ! empty($payload['subheading']))
            <div class="text-center max-w-2xl mx-auto mb-12">
                @if (! empty($payload['subheading']))
                    <span class="inline-block text-primary font-medium mb-3">{{ $payload['subheading'] }}</span>
                @endif
                @if (! empty($payload['heading']))
                    <h2 class="text-3xl md:text-4xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
                @endif
            </div>
        @endif
        @if (! empty($payload['body']))
            <p class="text-bodycolor text-center mb-10 max-w-2xl mx-auto">{{ $payload['body'] }}</p>
        @endif
        <div class="swiper service-swiper-4">
            <div class="swiper-wrapper">
                @forelse ($items as $item)
                    <div class="swiper-slide">
                        <div class="bg-white rounded-xl overflow-hidden shadow-lg h-full">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ $resolveMedia($item['image'] ?? null, $themes->assetUrl('images/services/large/img1.webp')) }}" alt="{{ $item['title'] ?? 'Service' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                @if (! empty($item['icon']))
                                    <span class="text-primary text-2xl mb-3 inline-block"><i class="{{ $item['icon'] }}"></i></span>
                                @endif
                                <h3 class="text-xl font-semibold text-secondary">
                                    @if (! empty($item['url']))
                                        <a href="{{ $item['url'] }}" class="hover:text-primary">{{ $item['title'] ?? '' }}</a>
                                    @else
                                        {{ $item['title'] ?? '' }}
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="bg-white rounded-xl p-8 shadow-lg text-center">
                            <p class="text-bodycolor">{{ $payload['body'] ?? 'Services coming soon.' }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        @if (! empty($payload['cta_label']))
            <div class="text-center mt-10">
                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}" class="btn btn-primary">
                    {{ $payload['cta_label'] }}
                </a>
            </div>
        @endif
    </div>
</section>
