@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $albums = \Modules\Website\Models\GalleryAlbum::query()->where('is_published', true)->orderBy('sort_order')->with('items')->get();
    $resolveMedia = fn (?string $path, string $fallback): string => \Modules\Website\Classes\Support\SafeUrl::media($path, $fallback);
@endphp
<section class="gallery-page section-space">
    <div class="container">
        @if (! empty($payload['heading']) || ! empty($payload['subheading']))
            <div class="sec-title sec-title--center wow fadeInUp" data-wow-duration="1500ms">
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
            <p class="about-one__text text-center mb-4">{{ $payload['body'] }}</p>
        @endif
        @foreach ($albums as $album)
            <div class="row gutter-y-30">
                @forelse ($album->items as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="gallery-page__card wow fadeInUp" data-wow-duration="1500ms">
                            <img src="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/gallery/gallery-1-3.jpg')) }}" alt="{{ $item->alt_text ?: $item->title ?: $album->title }}">
                            <div class="gallery-page__card__hover">
                                <a href="{{ $resolveMedia($item->image_path, $themes->assetUrl('images/gallery/gallery-1-3.jpg')) }}" class="img-popup">
                                    <span class="gallery-page__card__icon"></span>
                                </a>
                                <span class="gallery-page__card__hover__box gallery-page__card__hover__box--1"></span>
                                <span class="gallery-page__card__hover__box gallery-page__card__hover__box--2"></span>
                                <span class="gallery-page__card__hover__box gallery-page__card__hover__box--3"></span>
                                <span class="gallery-page__card__hover__box gallery-page__card__hover__box--4"></span>
                            </div>
                        </div>
                    </div>
                @empty
                    @if ($album->cover_image)
                        <div class="col-md-6 col-lg-4">
                            <div class="gallery-page__card">
                                <img src="{{ $resolveMedia($album->cover_image, $themes->assetUrl('images/gallery/gallery-1-3.jpg')) }}" alt="{{ $album->title }}">
                            </div>
                        </div>
                    @endif
                @endforelse
            </div>
        @endforeach
        @if (! empty($payload['cta_label']))
            <div class="text-center mt-4">
                <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null, route('website.gallery')) }}" class="mediox-btn">
                    <span>{{ $payload['cta_label'] }}</span>
                    <span class="mediox-btn__icon"><i class="icon-up-right-arrow"></i></span>
                </a>
            </div>
        @endif
    </div>
</section>
