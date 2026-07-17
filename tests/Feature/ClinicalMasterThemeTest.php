<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Enums\MenuItemType;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;
use Modules\Website\Models\Menu;
use Modules\Website\Models\Page;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

class ClinicalMasterThemeTest extends TestCase
{
    use DatabaseTransactions;
    use SeedsWebsiteSettings;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requireModule('Website');
        $this->migrateModules(['Website']);
        $this->seedWebsiteSettings();
    }

    public function test_theme_options_includes_clinicalmaster(): void
    {
        $options = app(ThemeManager::class)->themeOptions();

        $this->assertArrayHasKey('clinicalmaster', $options);
        $this->assertSame('ClinicalMaster (Physiotherapy)', $options['clinicalmaster']);
    }

    public function test_clinicalmaster_exposes_selectable_layouts(): void
    {
        $this->setActiveTheme('clinicalmaster');

        $layouts = app(ThemeManager::class)->layouts();

        $this->assertArrayHasKey('index', $layouts);
        $this->assertArrayHasKey('about-us', $layouts);
        $this->assertArrayHasKey('services', $layouts);
        $this->assertSame('index', app(ThemeManager::class)->defaultLayout());
    }

    public function test_page_view_name_uses_selected_layout(): void
    {
        $this->setActiveTheme('clinicalmaster');

        $manager = app(ThemeManager::class);

        $this->assertSame(
            'website::themes.clinicalmaster.pages.layouts.index',
            $manager->pageViewName(null)
        );
        $this->assertSame(
            'website::themes.clinicalmaster.pages.layouts.services',
            $manager->pageViewName('services')
        );
    }

    public function test_home_can_use_non_default_layout_shell(): void
    {
        $this->enablePublicWebsite();
        $this->setActiveTheme('clinicalmaster');

        Page::factory()->published()->create([
            'slug' => 'home',
            'type' => PageType::Home,
            'layout' => 'services',
            'title' => 'Services Home',
        ])->sections()->create([
            'type' => SectionType::Hero,
            'payload' => ['heading' => 'ClinicalMaster Services Layout'],
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $this->get('/')
            ->assertSuccessful()
            ->assertSee('ClinicalMaster Services Layout');
    }

    public function test_home_renders_header_nav_and_overrides_stylesheet(): void
    {
        $this->enablePublicWebsite();
        $this->setActiveTheme('clinicalmaster');

        Page::factory()->published()->create([
            'slug' => 'home',
            'type' => PageType::Home,
            'layout' => 'index',
            'title' => 'Home',
        ])->sections()->create([
            'type' => SectionType::Hero,
            'payload' => ['heading' => 'Welcome Clinic'],
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $response = $this->get('/')
            ->assertSuccessful()
            ->assertSee('cm-site-header', false)
            ->assertSee('cm-desktop-nav', false)
            ->assertSee('cm-logo-light', false)
            ->assertSee('cm-logo-dark', false)
            ->assertSee('cm-scroll-top', false)
            ->assertSee('app-overrides.css', false)
            ->assertSee('Welcome Clinic')
            ->assertDontSee('animation.js', false)
            ->assertDontSee('gsap is not defined')
            ->assertDontSee('class="scroll-top"', false);

        $html = $response->getContent();
        $this->assertSame(1, substr_count($html, 'cm-book-btn'));
        $this->assertSame(1, substr_count($html, 'cm-logo-link'));
    }

    public function test_empty_inner_page_keeps_banner_body_and_footer_structure(): void
    {
        $this->enablePublicWebsite();
        $this->setActiveTheme('clinicalmaster');

        Page::factory()->published()->create([
            'slug' => 'about',
            'type' => PageType::About,
            'layout' => 'about-us',
            'title' => 'About Us',
        ]);

        $this->get('/about')
            ->assertSuccessful()
            ->assertSee('cm-page-banner', false)
            ->assertSee('cm-page-body', false)
            ->assertSee('cm-empty-page', false)
            ->assertSee('cm-footer', false)
            ->assertSee('Content for this page will appear here');
    }

    public function test_nav_excludes_booking_menu_item_when_cta_is_present(): void
    {
        $this->enablePublicWebsite();
        $this->setActiveTheme('clinicalmaster');

        Page::factory()->published()->create([
            'slug' => 'home',
            'type' => PageType::Home,
            'layout' => 'index',
            'title' => 'Home',
        ]);

        $menu = Menu::query()->create([
            'name' => 'Primary',
            'location' => 'primary',
        ]);

        $menu->items()->create([
            'label' => 'Home',
            'type' => MenuItemType::Url,
            'url' => '/',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $menu->items()->create([
            'label' => 'Book Appointment',
            'type' => MenuItemType::Url,
            'url' => '/book-appointment',
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        $html = $this->get('/')->assertSuccessful()->getContent();

        $this->assertSame(1, substr_count($html, 'cm-book-btn'));
        $this->assertSame(1, substr_count($html, 'cm-mobile-book'));
        preg_match_all('/<ul class="cm-nav-list">(.*?)<\/ul>/s', $html, $navLists);
        $combinedNav = implode("\n", $navLists[1] ?? []);
        $this->assertStringNotContainsString('Book Appointment', $combinedNav);
        $this->assertStringContainsString('Home', $combinedNav);
    }

    protected function setActiveTheme(string $theme): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->active_theme = $theme;
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');
    }

    protected function enablePublicWebsite(string $slug = 'admin'): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->website_enabled = true;
        $settings->panel_path_slug = $slug;
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');

        require module_path('Website', 'routes/web.php');
        app('router')->getRoutes()->refreshNameLookups();
        app('router')->getRoutes()->refreshActionLookups();
    }
}
