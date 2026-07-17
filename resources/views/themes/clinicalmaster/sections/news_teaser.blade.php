@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $posts = \Modules\Website\Models\Post::query()->whereNotNull('published_at')->where('published_at', '<=', now())->latest('published_at')->limit(3)->get();
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
    $fallbackImage = $themes->assetUrl('images/blog/img1.webp');
@endphp
<section class="py-15 lg:py-20 bg-bglight">
    <div class="container">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
            <div class="max-w-2xl">
                @if (! empty($payload['heading']) || ! empty($payload['subheading']))
                    @if (! empty($payload['subheading']))
                        <span class="inline-block text-primary font-medium mb-3">{{ $payload['subheading'] }}</span>
                    @endif
                    @if (! empty($payload['heading']))
                        <h2 class="text-3xl md:text-4xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
                    @endif
                @endif
                @if (! empty($payload['body']))
                    <p class="text-bodycolor mt-4">{{ $payload['body'] }}</p>
                @endif
            </div>
        </div>
        <div class="swiper blog-slideshow">
            <div class="swiper-wrapper">
                @foreach ($posts as $post)
                    <div class="swiper-slide">
                        <article class="bg-white rounded-xl overflow-hidden shadow-lg h-full">
                            <div class="aspect-[16/10] overflow-hidden">
                                <img src="{{ $resolveMedia($post->cover_image, $fallbackImage) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6">
                                @if ($post->published_at)
                                    <time class="text-sm text-primary font-medium">{{ $post->published_at->toFormattedDateString() }}</time>
                                @endif
                                <h3 class="text-xl font-semibold text-secondary mt-2 mb-3">
                                    <a href="{{ route('website.news.show', $post->slug) }}" class="hover:text-primary">{{ $post->title }}</a>
                                </h3>
                                @if ($post->excerpt)
                                    <p class="text-bodycolor text-sm mb-4">{{ $post->excerpt }}</p>
                                @endif
                                <a href="{{ route('website.news.show', $post->slug) }}" class="btn btn-primary btn-sm">Read More</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
        @if (! empty($payload['cta_label']))
            <div class="text-center mt-10">
                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null, route('website.news.index')) }}" class="btn btn-primary">
                    {{ $payload['cta_label'] }}
                </a>
            </div>
        @endif
    </div>
</section>
