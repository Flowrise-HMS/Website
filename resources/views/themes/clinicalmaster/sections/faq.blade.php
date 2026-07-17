@php
    $items = $payload['items'] ?? [];
@endphp
<section class="py-15 lg:py-20 bg-bglight">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <div class="lg:col-span-7">
                @if (! empty($payload['heading']) || ! empty($payload['subheading']))
                    <div class="mb-8">
                        @if (! empty($payload['subheading']))
                            <span class="inline-block text-primary font-medium mb-3">{{ $payload['subheading'] }}</span>
                        @endif
                        @if (! empty($payload['heading']))
                            <h2 class="text-3xl md:text-4xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
                        @endif
                    </div>
                @endif
                <div class="myAccordion space-y-4">
                    @forelse ($items as $index => $item)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <button type="button" @class(['accordion-header w-full text-left px-6 py-4 flex items-center justify-between gap-4 font-semibold text-secondary', 'open' => $index === 0])>
                                <span>{{ $item['question'] ?? $item['title'] ?? '' }}</span>
                                <span class="arrow text-primary"><i class="feather icon-chevron-down"></i></span>
                            </button>
                            <div class="accordion-content px-6 pb-4 text-bodycolor" @if($index !== 0) style="max-height: 0;" @endif>
                                <p>{{ $item['answer'] ?? $item['body'] ?? '' }}</p>
                            </div>
                        </div>
                    @empty
                        @if (! empty($payload['body']))
                            <div class="bg-white rounded-xl p-6 shadow-sm text-bodycolor">
                                <p>{!! nl2br(e($payload['body'])) !!}</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
