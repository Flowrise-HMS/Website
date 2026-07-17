@php
    $menu = \Modules\Website\Models\Menu::query()->where('location', 'primary')->with(['items' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order')->with('page')])->first();
@endphp
<header class="website-header">
    <div class="website-container website-header__inner">
        <a class="website-brand" href="{{ route('website.home') }}">{{ config('app.name') }}</a>
        <nav class="website-nav" aria-label="Primary">
            @forelse ($menu?->items ?? [] as $item)
                @php
                    $href = match ($item->type->value) {
                        'page', 'cta' => $item->page ? route('website.page', $item->page->slug) : '#',
                        default => $item->url ?? '#',
                    };
                @endphp
                <a href="{{ $href }}" @if($item->open_in_new_tab) target="_blank" rel="noopener" @endif @class(['website-nav__link', 'website-nav__cta' => $item->type->value === 'cta'])>{{ $item->label }}</a>
            @empty
                <a class="website-nav__link" href="{{ route('website.home') }}">Home</a>
                <a class="website-nav__cta" href="{{ route('website.booking') }}">Book Appointment</a>
            @endforelse
        </nav>
    </div>
</header>
