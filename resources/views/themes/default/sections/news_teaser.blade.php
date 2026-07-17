<section class="website-section website-section--news_teaser" data-reveal>
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
        @foreach(\Modules\Website\Models\Post::query()->whereNotNull('published_at')->where('published_at', '<=', now())->latest('published_at')->limit(3)->get() as $post)
            <article class="website-card"><h3><a href="{{ route('website.news.show', $post->slug) }}">{{ $post->title }}</a></h3><p>{{ $post->excerpt }}</p></article>
        @endforeach
    </div>
</section>
