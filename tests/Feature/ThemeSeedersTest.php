<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Database\Seeders\Themes\ClinicalMasterThemeSeeder;
use Modules\Website\Database\Seeders\Themes\DefaultThemeSeeder;
use Modules\Website\Database\Seeders\Themes\MedioxThemeSeeder;
use Modules\Website\Database\Seeders\ThemeSeederRegistry;
use Modules\Website\Enums\PageType;
use Modules\Website\Models\Menu;
use Modules\Website\Models\Page;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ThemeSeedersTest extends TestCase
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

    public function test_registry_lists_all_theme_seeders(): void
    {
        $this->assertSame(
            [
                'default' => DefaultThemeSeeder::class,
                'mediox' => MedioxThemeSeeder::class,
                'clinicalmaster' => ClinicalMasterThemeSeeder::class,
            ],
            ThemeSeederRegistry::all()
        );
    }

    public function test_registry_rejects_unknown_theme(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown theme seeder [unknown]');

        ThemeSeederRegistry::resolve('unknown');
    }

    #[DataProvider('themeSeederProvider')]
    public function test_theme_seeder_activates_theme_and_creates_home_page(string $theme, string $seederClass, ?string $expectedHomeLayout): void
    {
        $seeder = app($seederClass);
        $seeder->setFresh(true);
        $seeder->run();

        $settings = app(WebsiteSettings::class);

        $this->assertSame($theme, $settings->active_theme);

        $home = Page::query()->where('slug', 'home')->first();
        $this->assertNotNull($home);
        $this->assertTrue($home->is_published);
        $this->assertSame(PageType::Home, $home->type);
        $this->assertSame($expectedHomeLayout, $home->layout);
        $this->assertGreaterThan(0, $home->sections()->count());

        $menu = Menu::query()->where('location', 'primary')->first();
        $this->assertNotNull($menu);
        $this->assertGreaterThan(0, $menu->items()->count());
    }

    public function test_seed_theme_command_seeds_clinicalmaster(): void
    {
        $this->artisan('website:seed-theme', [
            'theme' => 'clinicalmaster',
            '--fresh' => true,
        ])->assertSuccessful();

        $settings = app(WebsiteSettings::class);

        $this->assertSame('clinicalmaster', $settings->active_theme);
        $this->assertTrue(Page::query()->where('slug', 'home')->where('layout', 'index')->exists());
    }

    public function test_seed_theme_command_rejects_unknown_theme(): void
    {
        $this->artisan('website:seed-theme', ['theme' => 'does-not-exist'])
            ->assertFailed();
    }

    /**
     * @return array<string, array{0: string, 1: class-string, 2: ?string}>
     */
    public static function themeSeederProvider(): array
    {
        return [
            'default' => ['default', DefaultThemeSeeder::class, null],
            'mediox' => ['mediox', MedioxThemeSeeder::class, 'home-one'],
            'clinicalmaster' => ['clinicalmaster', ClinicalMasterThemeSeeder::class, 'index'],
        ];
    }
}
