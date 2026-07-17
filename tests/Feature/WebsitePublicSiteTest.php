<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Classes\Support\Website;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;
use Modules\Website\Livewire\ContactForm;
use Modules\Website\Models\ContactSubmission;
use Modules\Website\Models\Page;
use Modules\Website\Models\PageSection;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

class WebsitePublicSiteTest extends TestCase
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

    public function test_public_home_is_unavailable_when_website_disabled(): void
    {
        $this->setWebsiteEnabled(false);

        $this->assertFalse(Website::isPublicEnabled());
        $this->assertSame('/', Website::panelPath());
        $this->assertFalse(Route::has('website.home'));
    }

    public function test_public_home_renders_when_website_enabled(): void
    {
        $this->enablePublicWebsite('hms');

        $page = Page::factory()->published()->create([
            'slug' => 'home',
            'type' => PageType::Home,
            'title' => 'Hospital Home',
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'type' => SectionType::Hero,
            'payload' => ['heading' => 'Welcome patients'],
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $this->assertSame('hms', Website::panelPath());

        $this->get('/')
            ->assertSuccessful()
            ->assertSee('Welcome patients');
    }

    public function test_unpublished_page_returns_not_found(): void
    {
        $this->enablePublicWebsite();

        Page::factory()->create([
            'slug' => 'secret',
            'is_published' => false,
            'type' => PageType::Custom,
        ]);

        $this->get('/secret')->assertNotFound();
    }

    public function test_theme_manager_falls_back_to_default_section_view(): void
    {
        $manager = app(ThemeManager::class);

        $this->assertSame(
            'website::themes.default.sections.hero',
            $manager->sectionViewName('hero')
        );
    }

    public function test_mediox_theme_appears_in_theme_options(): void
    {
        $manager = app(ThemeManager::class);
        $options = $manager->themeOptions();

        $this->assertArrayHasKey('mediox', $options);
        $this->assertSame('Mediox (Home One)', $options['mediox']);
    }

    public function test_theme_manager_asset_url(): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->active_theme = 'mediox';
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');

        $manager = app(ThemeManager::class);

        $this->assertSame(
            asset('site-themes/mediox/assets/css/mediox.css'),
            $manager->assetUrl('css/mediox.css')
        );
        $this->assertSame(
            asset('site-themes/mediox/assets/vendors/jquery/jquery-3.7.0.min.js'),
            $manager->assetUrl('/vendors/jquery/jquery-3.7.0.min.js')
        );
    }

    public function test_theme_manager_layout_view(): void
    {
        $manager = app(ThemeManager::class);

        $settings = app(WebsiteSettings::class);
        $settings->active_theme = 'mediox';
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');

        $this->assertSame(
            'website::themes.mediox.layouts.app',
            $manager->layoutView()
        );
    }

    public function test_contact_form_persists_submission(): void
    {
        $this->enablePublicWebsite();

        Livewire::test(ContactForm::class)
            ->set('name', 'Ama Boateng')
            ->set('email', 'ama@example.com')
            ->set('message', 'Please call me about clinics.')
            ->call('submit')
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('website_contact_submissions', [
            'email' => 'ama@example.com',
            'name' => 'Ama Boateng',
        ]);
        $this->assertSame(1, ContactSubmission::query()->count());
    }

    public function test_publish_theme_assets_strips_php_scripts(): void
    {
        $themes = app(ThemeManager::class);
        $sourceInc = $themes->publishableAssetsPath('mediox').'/assets/inc';
        $publicScript = $themes->publicAssetsPath('mediox').'/inc/sendemail.php';

        if (! is_dir($sourceInc)) {
            mkdir($sourceInc, 0777, true);
        }

        file_put_contents($sourceInc.'/sendemail.php', "<?php mail('a@b.c','x','y');");

        try {
            $this->artisan('website:publish-theme-assets', ['theme' => 'mediox'])
                ->assertSuccessful();

            $this->assertFileDoesNotExist($publicScript);
        } finally {
            if (is_file($sourceInc.'/sendemail.php')) {
                unlink($sourceInc.'/sendemail.php');
            }
        }
    }

    protected function enablePublicWebsite(string $slug = 'admin'): void
    {
        $this->setWebsiteEnabled(true, $slug);

        require module_path('Website', 'routes/web.php');
        app('router')->getRoutes()->refreshNameLookups();
        app('router')->getRoutes()->refreshActionLookups();
    }

    protected function setWebsiteEnabled(bool $enabled, string $slug = 'admin'): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->website_enabled = $enabled;
        $settings->panel_path_slug = $slug;
        $settings->active_theme = 'default';
        $settings->save();

        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');
    }
}
