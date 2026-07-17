<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('website', function ($blueprint): void {
            $blueprint->add('website_enabled', false);
            $blueprint->add('panel_path_slug', 'admin');
            $blueprint->add('active_theme', 'default');
            $blueprint->add('animations_enabled', true);
            $blueprint->add('meta_title', null);
            $blueprint->add('meta_description', null);
            $blueprint->add('contact_email', null);
            $blueprint->add('contact_phone', null);
            $blueprint->add('booking_cta_phone', null);
            $blueprint->add('booking_cta_whatsapp', null);
            $blueprint->add('booking_cta_message', null);
        });
    }
};
