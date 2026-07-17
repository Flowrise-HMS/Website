@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $items = $payload['items'] ?? [];
@endphp
<section class="funfact-one">
    <div class="funfact-one__bg" style="background-image: url({{ $themes->assetUrl('images/resources/funfact-bg-1-1.jpg') }});"></div>
    <div class="container">
        @if (! empty($payload['heading']))
            <div class="sec-title sec-title--center wow fadeInUp" data-wow-duration="1500ms">
                <h3 class="sec-title__title">{{ $payload['heading'] }}</h3>
                @if (! empty($payload['body']))
                    <p class="about-one__text">{{ $payload['body'] }}</p>
                @endif
            </div>
        @endif
        <div class="funfact-one__row">
            @forelse ($items as $index => $item)
                <div class="funfact-one__item wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $index * 100 }}ms">
                    <h3 class="funfact-one__item__number count-box">
                        <span class="count-text" data-stop="{{ $item['value'] ?? 0 }}" data-speed="1500"></span>
                        @if (! empty($item['suffix']))
                            <span>{{ $item['suffix'] }}</span>
                        @endif
                    </h3>
                    <p class="funfact-one__item__title">{{ $item['label'] ?? '' }}</p>
                </div>
            @empty
                @if (! empty($payload['body']) && empty($payload['heading']))
                    <div class="funfact-one__item">
                        <p class="funfact-one__item__title">{!! nl2br(e($payload['body'])) !!}</p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
</section>
