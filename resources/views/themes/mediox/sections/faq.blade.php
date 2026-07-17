@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $items = $payload['items'] ?? [];
@endphp
<section class="faq-one">
    <div class="faq-one__bg" style="background-image: url({{ $themes->assetUrl('images/shapes/faq-bg-1-1.png') }});"></div>
    <div class="container section-space-two">
        <div class="row gutter-y-50">
            <div class="col-xl-6 col-lg-9">
                <div class="faq-one__content">
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
                    <div class="faq-one__accordion">
                        <div class="faq-accordion mediox-accordion" data-grp-name="mediox-accordion">
                            @forelse ($items as $index => $item)
                                <div @class(['accordion', 'active' => $index === 0, 'wow fadeInUp']) data-wow-duration="1500ms" data-wow-delay="{{ $index * 50 }}ms">
                                    <div class="accordion-title">
                                        <h4>
                                            {{ $item['question'] ?? $item['title'] ?? '' }}
                                            <span class="accordion-title__icon"></span>
                                        </h4>
                                    </div>
                                    <div class="accordion-content">
                                        <div class="inner">
                                            <p>{{ $item['answer'] ?? $item['body'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @if (! empty($payload['body']))
                                    <div class="accordion active">
                                        <div class="accordion-content">
                                            <div class="inner">
                                                <p>{!! nl2br(e($payload['body'])) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 wow fadeInRight" data-wow-duration="1500ms">
                <div class="faq-one__image">
                    <img src="{{ $themes->assetUrl('images/resources/faq-1-1.png') }}" alt="" class="faq-one__image__one">
                    <img src="{{ $themes->assetUrl('images/resources/faq-1-2.png') }}" alt="" class="faq-one__image__two">
                    <img src="{{ $themes->assetUrl('images/shapes/faq-shape-1-1.png') }}" alt="" class="faq-one__image__shape-1">
                </div>
            </div>
        </div>
    </div>
</section>
