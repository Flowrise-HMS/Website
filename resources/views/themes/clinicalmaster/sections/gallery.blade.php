@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $albums = \Modules\Website\Models\GalleryAlbum::query()->where('is_published', true)->orderBy('sort_order')->with('items')->get();
    $resolveMedia = fn (?string $path, string $fallback): string => \Modules\Website\Classes\Support\SafeUrl::media($path, $fallback);
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
        @foreach ($albums as $album)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @forelse ($album->items as $item)
                    <div class="rounded-xl overflow-hidden shadow-md group relative">
                        <img src="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/post/img1.webp')) }}" alt="{{ $item->alt_text ?: $item->title ?: $album->title }}" class="w-full aspect-[4/3] object-cover">
                        <a href="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/post/img1.webp')) }}" class="absolute inset-0 bg-secondary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                            <i class="feather icon-maximize text-2xl"></i>
                        </a>
                    </div>
                @empty
                    @if ($album->cover_image)
                        <div class="rounded-xl overflow-hidden shadow-md">
                            <img src="{{ $resolveMedia($album->cover_image, $themes->assetUrl('images/post/img1.webp')) }}" alt="{{ $album->title }}" class="w-full aspect-[4/3] object-cover">
                        </div>
                    @endif
                @endforelse
            </div>
        @endforeach
        @if (! empty($payload['cta_label']))
            <div class="text-center mt-6">
                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null, route('website.gallery')) }}" class="btn btn-primary">
                    {{ $payload['cta_label'] }}
                </a>
            </div>
        @endif
    </div>
</section>
