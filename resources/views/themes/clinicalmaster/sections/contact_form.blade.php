<section class="py-15 lg:py-20">
    <div class="container">
        <div class="max-w-3xl mx-auto">
            @if (! empty($payload['heading']) || ! empty($payload['subheading']))
                <div class="mb-8 text-center">
                    @if (! empty($payload['subheading']))
                        <span class="inline-block text-primary font-medium mb-3">{{ $payload['subheading'] }}</span>
                    @endif
                    @if (! empty($payload['heading']))
                        <h2 class="text-3xl md:text-4xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
                    @endif
                </div>
            @endif
            @if (! empty($payload['body']))
                <p class="text-bodycolor text-center mb-8">{{ $payload['body'] }}</p>
            @endif
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-10">
                @livewire(\Modules\Website\Livewire\ContactForm::class)
            </div>
        </div>
    </div>
</section>
