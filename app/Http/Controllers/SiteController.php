<?php

namespace Modules\Website\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Website\Classes\Support\PageRenderer;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Classes\Support\Website;
use Modules\Website\Contracts\BookingCtaResolver;
use Modules\Website\Enums\PageType;
use Modules\Website\Models\GalleryAlbum;
use Modules\Website\Models\Page;
use Modules\Website\Models\Partner;
use Modules\Website\Models\Post;
use Modules\Website\Models\TeamMember;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    public function home(PageRenderer $renderer, ThemeManager $themes)
    {
        $page = Page::query()->published()->where('type', PageType::Home)->with('sections')->first()
            ?? Page::query()->published()->where('slug', 'home')->with('sections')->firstOrFail();

        return $renderer->render($page)->with([
            'metaTitle' => $page->meta_title ?: Website::settings()->meta_title ?: $page->title,
            'metaDescription' => $page->meta_description ?: Website::settings()->meta_description,
            'animationsEnabled' => Website::settings()->animations_enabled,
            'jsonLd' => $this->organizationJsonLd(),
        ]);
    }

    public function page(string $slug, PageRenderer $renderer)
    {
        $page = Page::query()->published()->where('slug', $slug)->with('sections')->firstOrFail();

        return $renderer->render($page);
    }

    public function newsIndex(ThemeManager $themes)
    {
        $posts = Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);

        return $themes->view('pages.news-index', [
            'posts' => $posts,
            'metaTitle' => 'News',
            'animationsEnabled' => Website::settings()->animations_enabled,
        ]);
    }

    public function newsShow(string $slug, ThemeManager $themes)
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return $themes->view('pages.news-show', [
            'post' => $post,
            'metaTitle' => $post->meta_title ?: $post->title,
            'metaDescription' => $post->meta_description ?: $post->excerpt,
            'animationsEnabled' => Website::settings()->animations_enabled,
        ]);
    }

    public function gallery(ThemeManager $themes)
    {
        $albums = GalleryAlbum::query()->where('is_published', true)->orderBy('sort_order')->with('items')->get();

        return $themes->view('pages.gallery', [
            'albums' => $albums,
            'metaTitle' => 'Gallery',
            'animationsEnabled' => Website::settings()->animations_enabled,
        ]);
    }

    public function team(ThemeManager $themes)
    {
        $members = TeamMember::query()->where('is_published', true)->orderBy('sort_order')->get();

        return $themes->view('pages.team', [
            'members' => $members,
            'metaTitle' => 'Our Team',
            'animationsEnabled' => Website::settings()->animations_enabled,
        ]);
    }

    public function partners(ThemeManager $themes)
    {
        $partners = Partner::query()->where('is_published', true)->orderBy('sort_order')->get();

        return $themes->view('pages.partners', [
            'partners' => $partners,
            'metaTitle' => 'Partners',
            'animationsEnabled' => Website::settings()->animations_enabled,
        ]);
    }

    public function contact(ThemeManager $themes)
    {
        $page = Page::query()->published()->where('type', PageType::Contact)->with('sections')->first();

        if ($page) {
            return app(PageRenderer::class)->render($page);
        }

        return $themes->view('pages.contact', [
            'metaTitle' => 'Contact Us',
            'animationsEnabled' => Website::settings()->animations_enabled,
        ]);
    }

    public function booking(ThemeManager $themes, BookingCtaResolver $booking)
    {
        return $themes->view('pages.booking', [
            'booking' => $booking->resolve(),
            'metaTitle' => 'Book Appointment',
            'animationsEnabled' => Website::settings()->animations_enabled,
        ]);
    }

    public function sitemap(): Response
    {
        $pages = Page::query()->published()->get(['slug', 'updated_at']);
        $posts = Post::query()->whereNotNull('published_at')->where('published_at', '<=', now())->get(['slug', 'updated_at']);

        $xml = view('website::sitemap', compact('pages', 'posts'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    /**
     * @return array<string, mixed>
     */
    protected function organizationJsonLd(): array
    {
        $settings = Website::settings();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => $settings->meta_title ?: config('app.name'),
            'description' => $settings->meta_description,
            'email' => $settings->contact_email,
            'telephone' => $settings->contact_phone,
            'url' => url('/'),
        ];
    }
}
