<?php

namespace Modules\Website\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Classes\Support\ThemeBranding;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

class ThemeBrandingTest extends TestCase
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

    public function test_logo_urls_fall_back_to_theme_assets(): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->active_theme = 'mediox';
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');

        $branding = app(ThemeBranding::class);
        $themes = app(ThemeManager::class);

        $this->assertSame($themes->assetUrl('images/logo-dark.png'), $branding->logoDarkUrl());
        $this->assertSame($themes->assetUrl('images/logo-light.png'), $branding->logoLightUrl());
    }

    public function test_css_variables_include_configured_colors(): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->brand_primary_color = '#112233';
        $settings->brand_secondary_color = '#445566';
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');

        $style = app(ThemeBranding::class)->cssVariablesStyle();

        $this->assertStringContainsString('--website-brand-primary: #112233;', $style);
        $this->assertStringContainsString('--website-brand-secondary: #445566;', $style);
    }
}
