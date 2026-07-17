<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;
use Modules\Website\Models\Page;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

class MedioxThemeTest extends TestCase
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

    public function test_theme_options_includes_mediox_when_active_theme_is_mediox(): void
    {
        $this->setActiveTheme('mediox');

        $options = app(ThemeManager::class)->themeOptions();

        $this->assertArrayHasKey('mediox', $options);
        $this->assertSame('Mediox (Home One)', $options['mediox']);
    }

    public function test_section_view_name_returns_mediox_section_when_theme_is_mediox(): void
    {
        $this->setActiveTheme('mediox');

        $manager = app(ThemeManager::class);

        foreach (SectionType::values() as $sectionType) {
            $this->assertSame(
                "website::themes.mediox.sections.{$sectionType}",
                $manager->sectionViewName($sectionType),
                "Expected mediox section view for {$sectionType}"
            );
        }
    }

    public function test_mediox_home_renders_cms_hero_section(): void
    {
        $this->enablePublicWebsite();
        $this->setActiveTheme('mediox');

        Page::factory()->published()->create([
            'slug' => 'home',
            'type' => PageType::Home,
            'title' => 'Hospital Home',
        ])->sections()->create([
            'type' => SectionType::Hero,
            'payload' => ['heading' => 'Mediox Hero Heading'],
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $this->get('/')
            ->assertSuccessful()
            ->assertSee('Mediox Hero Heading');
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
