@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
@endphp
<section class="contact-page section-space">
    <div class="contact-page__inner section-space">
        <div class="container">
            <div class="row gutter-y-60">
                <div class="col-lg-8">
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
                    <div class="contact-page__form">
                        @livewire(\Modules\Website\Livewire\ContactForm::class)
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
