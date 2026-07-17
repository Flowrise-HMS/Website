<?php

namespace Modules\Website\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Classes\Support\NullBookingCtaResolver;
use Modules\Website\Classes\Support\Website;
use Modules\Website\Contracts\BookingCtaResolver;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

class WebsiteSupportTest extends TestCase
{
    use DatabaseTransactions;
    use SeedsWebsiteSettings;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requireModule('Website');
        $this->migrateModules();
        $this->seedWebsiteSettings();
    }

    public function test_module_is_enabled(): void
    {
        $this->assertTrue(Website::moduleEnabled());
    }

    public function test_panel_path_is_root_when_public_site_disabled(): void
    {
        $this->assertFalse(Website::isPublicEnabled());
        $this->assertSame('/', Website::panelPath());
    }

    public function test_panel_path_uses_slug_when_public_site_enabled(): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->website_enabled = true;
        $settings->panel_path_slug = 'admin';
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);

        $this->assertTrue(Website::isPublicEnabled());
        $this->assertSame('admin', Website::panelPath());
    }

    public function test_null_booking_cta_resolver_is_bound_and_reads_settings(): void
    {
        $settings = app(WebsiteSettings::class);
        $settings->booking_cta_phone = '+233200000000';
        $settings->booking_cta_whatsapp = '+233200000000';
        $settings->booking_cta_message = 'Book a visit';
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);

        $resolver = app(BookingCtaResolver::class);

        $this->assertInstanceOf(NullBookingCtaResolver::class, $resolver);

        $cta = $resolver->resolve();

        $this->assertSame('+233200000000', $cta['phone']);
        $this->assertSame('+233200000000', $cta['whatsapp']);
        $this->assertSame('Book a visit', $cta['message']);
        $this->assertSame('https://wa.me/233200000000?text=Book%20a%20visit', $cta['url']);
    }
}
