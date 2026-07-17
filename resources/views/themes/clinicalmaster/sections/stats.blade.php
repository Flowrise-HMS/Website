@php
    $items = $payload['items'] ?? [];
@endphp
<section class="py-15 lg:py-20 bg-bglight">
    <div class="container">
        @if (! empty($payload['heading']))
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
                @if (! empty($payload['body']))
                    <p class="text-bodycolor mt-4">{{ $payload['body'] }}</p>
                @endif
            </div>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
            @forelse ($items as $item)
                <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                    <h3 class="text-4xl font-semibold text-primary mb-2">
                        <span class="value" data-value="{{ $item['value'] ?? 0 }}">0</span>@if (! empty($item['suffix']))<span>{{ $item['suffix'] }}</span>@endif
                    </h3>
                    <p class="text-bodycolor font-medium">{{ $item['label'] ?? '' }}</p>
                </div>
            @empty
                @if (! empty($payload['body']) && empty($payload['heading']))
                    <div class="col-span-full text-center text-bodycolor">
                        <p>{!! nl2br(e($payload['body'])) !!}</p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
</section>
