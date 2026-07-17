@php
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
    <section class="pt-15 pb-6">
        <div class="container text-center max-w-2xl mx-auto">
            @if (! empty($payload['heading']))
                <h2 class="text-3xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
            @endif
            @if (! empty($payload['body']))
                <p class="text-bodycolor mt-4">{{ $payload['body'] }}</p>
            @endif
        </div>
    </section>
@endif
<section class="pb-15 lg:pb-20">
    <div class="container">
        <div class="swiper client-swiper2">
            <div class="swiper-wrapper items-center">
                @foreach ($partners as $partner)
                    <div class="swiper-slide">
                        <div class="flex items-center justify-center p-4">
                            @php $logoUrl = $resolveMedia($partner->logo); @endphp
                            @if ($logoUrl)
                                @if ($partner->url)
                                    <a href="{{ $partner->url }}" rel="noopener" target="_blank">
                                        <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="max-h-14 w-auto object-contain mx-auto">
                                    </a>
                                @else
                                    <img src="{{ $logoUrl }}" alt="{{ $partner->name }}" class="max-h-14 w-auto object-contain mx-auto">
                                @endif
                            @else
                                <span class="text-secondary font-medium">{{ $partner->name }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
