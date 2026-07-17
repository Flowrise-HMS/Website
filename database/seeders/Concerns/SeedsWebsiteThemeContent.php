<?php

namespace Modules\Website\Database\Seeders\Concerns;

use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Enums\MenuItemType;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;
use Modules\Website\Models\Menu;
use Modules\Website\Models\MenuItem;
use Modules\Website\Models\Page;
use Modules\Website\Models\PageSection;
use Modules\Website\Models\Partner;
use Modules\Website\Models\Post;
use Modules\Website\Models\TeamMember;
use Modules\Website\Settings\WebsiteSettings;

trait SeedsWebsiteThemeContent
{
    protected bool $fresh = false;

    public function setFresh(bool $fresh): static
    {
        $this->fresh = $fresh;

        return $this;
    }

    protected function activateTheme(string $theme, array $settings = []): void
    {
        $manager = app(ThemeManager::class);

        if (! $manager->themeExists($theme)) {
            throw new \InvalidArgumentException("Unknown website theme [{$theme}].");
        }

        $website = app(WebsiteSettings::class);
        $website->active_theme = $theme;
        $website->website_enabled = $settings['website_enabled'] ?? $website->website_enabled;
        $website->panel_path_slug = $settings['panel_path_slug'] ?? ($website->panel_path_slug ?: 'admin');
        $website->meta_title = $settings['meta_title'] ?? $website->meta_title;
        $website->meta_description = $settings['meta_description'] ?? $website->meta_description;
        $website->footer_about_text = $settings['footer_about_text'] ?? $website->footer_about_text;
        $website->brand_primary_color = $settings['brand_primary_color'] ?? $website->brand_primary_color;
        $website->brand_secondary_color = $settings['brand_secondary_color'] ?? $website->brand_secondary_color;
        $website->contact_email = $settings['contact_email'] ?? $website->contact_email;
        $website->contact_phone = $settings['contact_phone'] ?? $website->contact_phone;
        $website->save();

        app()->forgetInstance(WebsiteSettings::class);
    }

    /**
     * @param  array{slug: string, title: string, type: PageType, layout?: ?string, meta_title?: ?string, meta_description?: ?string, sort_order?: int, sections?: list<array{type: SectionType, payload: array<string, mixed>, sort_order?: int}>}  $definition
     */
    protected function seedPage(array $definition): Page
    {
        $page = Page::query()->updateOrCreate(
            ['slug' => $definition['slug']],
            [
                'title' => $definition['title'],
                'type' => $definition['type'],
                'layout' => $definition['layout'] ?? null,
                'is_published' => true,
                'sort_order' => $definition['sort_order'] ?? 0,
                'meta_title' => $definition['meta_title'] ?? $definition['title'],
                'meta_description' => $definition['meta_description'] ?? null,
            ]
        );

        $sections = $definition['sections'] ?? [];

        if ($sections === []) {
            return $page;
        }

        if ($this->fresh || $page->sections()->count() === 0) {
            if ($this->fresh) {
                $page->sections()->delete();
            }

            foreach ($sections as $index => $section) {
                PageSection::query()->create([
                    'page_id' => $page->id,
                    'type' => $section['type'],
                    'payload' => $section['payload'],
                    'sort_order' => $section['sort_order'] ?? ($index + 1),
                    'is_visible' => true,
                ]);
            }
        }

        return $page->fresh(['sections']);
    }

    /**
     * @param  list<array{label: string, type: MenuItemType, page_slug?: ?string, url?: ?string}>  $items
     */
    protected function seedPrimaryMenu(array $items): Menu
    {
        $menu = Menu::query()->updateOrCreate(
            ['location' => 'primary'],
            ['name' => 'Primary']
        );

        if (! $this->fresh && $menu->items()->count() > 0) {
            return $menu;
        }

        if ($this->fresh) {
            $menu->items()->delete();
        }

        foreach ($items as $sort => $item) {
            $pageId = null;
            if (($item['type'] === MenuItemType::Page || $item['type'] === MenuItemType::Cta) && ! empty($item['page_slug'])) {
                $pageId = Page::query()->where('slug', $item['page_slug'])->value('id');
            }

            MenuItem::query()->create([
                'menu_id' => $menu->id,
                'label' => $item['label'],
                'type' => $item['type'] === MenuItemType::Cta ? MenuItemType::Url : $item['type'],
                'page_id' => $item['type'] === MenuItemType::Page ? $pageId : null,
                'url' => $item['type'] === MenuItemType::Page ? null : ($item['url'] ?? null),
                'sort_order' => $sort + 1,
                'is_visible' => true,
            ]);
        }

        return $menu->fresh(['items']);
    }

    /**
     * @param  list<array{name: string, role: string, bio?: ?string}>  $members
     */
    protected function seedTeamMembers(array $members): void
    {
        if (! $this->fresh && TeamMember::query()->exists()) {
            return;
        }

        if ($this->fresh) {
            TeamMember::query()->delete();
        }

        foreach ($members as $sort => $member) {
            TeamMember::query()->create([
                'name' => $member['name'],
                'role' => $member['role'],
                'bio' => $member['bio'] ?? null,
                'is_published' => true,
                'sort_order' => $sort + 1,
            ]);
        }
    }

    /**
     * @param  list<array{name: string, url?: ?string}>  $partners
     */
    protected function seedPartners(array $partners): void
    {
        if (! $this->fresh && Partner::query()->exists()) {
            return;
        }

        if ($this->fresh) {
            Partner::query()->delete();
        }

        foreach ($partners as $sort => $partner) {
            Partner::query()->create([
                'name' => $partner['name'],
                'url' => $partner['url'] ?? null,
                'is_published' => true,
                'sort_order' => $sort + 1,
            ]);
        }
    }

    /**
     * @param  list<array{title: string, slug: string, excerpt?: ?string, body?: ?string}>  $posts
     */
    protected function seedPosts(array $posts): void
    {
        if (! $this->fresh && Post::query()->exists()) {
            return;
        }

        if ($this->fresh) {
            Post::query()->delete();
        }

        foreach ($posts as $index => $post) {
            Post::query()->create([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'excerpt' => $post['excerpt'] ?? null,
                'body' => $post['body'] ?? null,
                'published_at' => now()->subDays($index + 1),
            ]);
        }
    }
}
