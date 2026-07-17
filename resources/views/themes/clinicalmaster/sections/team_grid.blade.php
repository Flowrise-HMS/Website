@php
    $themes = app(\Modules\Website\Classes\Support\ThemeManager::class);
    $members = \Modules\Website\Models\TeamMember::query()->where('is_published', true)->orderBy('sort_order')->get();
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
<section class="py-15 lg:py-20 bg-bglight">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center mb-12">
            <div class="lg:col-span-5">
                @if (! empty($payload['heading']) || ! empty($payload['subheading']))
                    @if (! empty($payload['subheading']))
                        <span class="inline-block text-primary font-medium mb-3">{{ $payload['subheading'] }}</span>
                    @endif
                    @if (! empty($payload['heading']))
                        <h2 class="text-3xl md:text-4xl font-semibold text-secondary">{{ $payload['heading'] }}</h2>
                    @endif
                @endif
                @if (! empty($payload['body']))
                    <p class="text-bodycolor mt-4 leading-relaxed">{{ $payload['body'] }}</p>
                @endif
                @if (! empty($payload['cta_label']))
                    <a href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null, route('website.team')) }}" class="btn btn-primary mt-6">
                        {{ $payload['cta_label'] }}
                    </a>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($members as $member)
                <div class="bg-white rounded-xl overflow-hidden shadow-lg group">
                    <div class="aspect-[3/4] overflow-hidden">
                        <img src="{{ $resolveMedia($member->photo, $themes->assetUrl('images/post/img1.webp')) }}" alt="{{ $member->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-semibold text-secondary">{{ $member->name }}</h3>
                        <p class="text-primary text-sm mt-1">{{ $member->role }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
