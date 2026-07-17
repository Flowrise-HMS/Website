<section class="website-section website-section--team_grid" data-reveal>
    <div class="website-container">
        @if(!empty($payload['heading']))
            <h2 class="website-section__title">{{ $payload['heading'] }}</h2>
        @endif
        @if(!empty($payload['subheading']))
            <p class="website-section__sub">{{ $payload['subheading'] }}</p>
        @endif
        @if(!empty($payload['body']))
            <div class="website-prose">{!! nl2br(e($payload['body'])) !!}</div>
        @endif
        @if(!empty($payload['cta_label']))
            <p><a class="website-btn" href="{{ \Modules\Website\Classes\Support\SafeUrl::href($payload['cta_url'] ?? null) }}">{{ $payload['cta_label'] }}</a></p>
        @endif
        @foreach(\Modules\Website\Models\TeamMember::query()->where('is_published', true)->orderBy('sort_order')->get() as $member)
            <article class="website-card"><h3>{{ $member->name }}</h3><p>{{ $member->role }}</p></article>
        @endforeach
    </div>
</section>
