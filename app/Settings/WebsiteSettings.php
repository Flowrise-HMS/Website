<?php

namespace Modules\Website\Settings;

use Spatie\LaravelSettings\Settings;

class WebsiteSettings extends Settings
{
    public bool $website_enabled = false;

    public string $panel_path_slug = 'admin';

    public string $active_theme = 'default';

    public bool $animations_enabled = true;

    public ?string $meta_title = null;

    public ?string $meta_description = null;

    public ?string $contact_email = null;

    public ?string $contact_phone = null;

    public ?string $booking_cta_phone = null;

    public ?string $booking_cta_whatsapp = null;

    public ?string $booking_cta_message = null;

    /** @var list<string> */
    public array $bookable_service_ids = [];

    public ?string $booking_branch_id = null;

    /** @var list<int> ISO-8601 weekdays (1=Monday … 7=Sunday) */
    public array $booking_open_weekdays = [1, 2, 3, 4, 5];

    public string $booking_open_time = '08:00';

    public string $booking_close_time = '17:00';

    public int $booking_slot_minutes = 30;

    public int $booking_horizon_days = 14;

    public ?string $brand_logo_path = null;

    public ?string $brand_logo_light_path = null;

    public ?string $brand_favicon_path = null;

    public ?string $brand_primary_color = null;

    public ?string $brand_secondary_color = null;

    public ?string $footer_about_text = null;

    public static function group(): string
    {
        return 'website';
    }
}
