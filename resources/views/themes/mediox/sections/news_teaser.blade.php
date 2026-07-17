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
@endphp
<section class="blog-one section-space">
    <div class="blog-one__bg" style="background-image: url({{ $themes->assetUrl('images/shapes/blog-bg-1-1.png') }});"></div>
    <div class="container">
        <div class="blog-one__top">
            <div class="row gutter-y-40 align-items-end">
                <div class="col-xl-9 col-lg-8">
                    @if (! empty($payload['heading']) || ! empty($payload['subheading']))
                        <div class="sec-title wow fadeInUp" data-wow-duration="1500ms">
                            <div class="sec-title__top">
                                <img src="{{ $themes->assetUrl('images/shapes/sec-title-s-1-1.png') }}" alt="" class="sec-title__img">
                                @if (! empty($payload['subheading']))
                                    <h6 class="sec-title__tagline">{{ $payload['subheading'] }}</h6>
                                @endif
                            </div>
                            @if (! empty($payload['heading']))
                                <h3 class="sec-title__title">{{ $payload['heading'] }}</h3>
                            @endif
                        </div>
                    @endif
                    @if (! empty($payload['body']))
                        <p class="about-one__text">{{ $payload['body'] }}</p>
                    @endif
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="blog-one__custome-navs"></div>
                </div>
            </div>
        </div>
        <div class="blog-one__carousel mediox-owl__carousel mediox-owl__carousel--basic-nav owl-carousel owl-theme" data-owl-options='{"items": 1, "margin": 10, "loop": true, "smartSpeed": 700, "navContainer": ".blog-one__custome-navs", "nav": true, "dots": false, "autoplay": true, "responsive": {"0": {"items": 1}, "768": {"items": 2, "margin": 30}}}'>
            @foreach ($posts as $index => $post)
                <div class="item">
                    <div class="blog-card wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $index * 100 }}ms">
                        <div class="blog-card__image">
                            <img src="{{ $resolveMedia($post->cover_image, $themes->assetUrl('images/blog/blog-1-1.jpg')) }}" alt="{{ $post->title }}">
                            <a href="{{ route('website.news.show', $post->slug) }}" class="blog-card__hover">
                                <span class="sr-only">{{ $post->title }}</span>
                                <span class="blog-card__hover__icon"></span>
                            </a>
                        </div>
                        <div class="blog-card__content">
                            @if ($post->published_at)
                                <ul class="list-unstyled blog-card__meta">
                                    <li>{{ $post->published_at->toFormattedDateString() }}</li>
                                </ul>
                            @endif
                            <h3 class="blog-card__title">
                                <a href="{{ route('website.news.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            @if ($post->excerpt)
                                <p>{{ $post->excerpt }}</p>
                            @endif
                            <a href="{{ route('website.news.show', $post->slug) }}" class="blog-card__btn">
                                <span class="blog-card__btn__icon"><i class="icon-arrow-right-2"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if (! empty($payload['cta_label']))
            <div class="text-center mt-4">
                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null, route('website.news.index')) }}" class="mediox-btn">
                    <span>{{ $payload['cta_label'] }}</span>
                    <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                </a>
            </div>
        @endif
    </div>
</section>
