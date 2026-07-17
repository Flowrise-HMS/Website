<?php

namespace Modules\Website\Tests\Concerns;

use Illuminate\Support\Facades\DB;
use Modules\Website\Settings\WebsiteSettings;

trait SeedsWebsiteSettings
{
    protected function websiteSettingsDefaults(): array
    {
        return [
            'website_enabled' => false,
            'panel_path_slug' => 'admin',
            'active_theme' => 'default',
            'animations_enabled' => true,
            'meta_title' => null,
            'meta_description' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'booking_cta_phone' => null,
            'booking_cta_whatsapp' => null,
            'booking_cta_message' => null,
            'bookable_service_ids' => [],
            'booking_branch_id' => null,
            'booking_open_weekdays' => [1, 2, 3, 4, 5],
            'booking_open_time' => '08:00',
            'booking_close_time' => '17:00',
            'booking_slot_minutes' => 30,
            'booking_horizon_days' => 14,
            'brand_logo_path' => null,
            'brand_logo_light_path' => null,
            'brand_favicon_path' => null,
            'brand_primary_color' => null,
            'brand_secondary_color' => null,
            'footer_about_text' => null,
        ];
    }

    protected function seedWebsiteSettings(): void
    {
        foreach ($this->websiteSettingsDefaults() as $name => $value) {
            DB::table('settings')->updateOrInsert(
                ['group' => 'website', 'name' => $name],
                [
                    'payload' => json_encode($value, JSON_THROW_ON_ERROR),
                    'locked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        app()->forgetInstance(WebsiteSettings::class);
        $this->artisan('settings:clear-cache');
    }
}
