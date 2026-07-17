<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('website', function ($blueprint): void {
            $blueprint->add('brand_logo_path', null);
            $blueprint->add('brand_logo_light_path', null);
            $blueprint->add('brand_favicon_path', null);
            $blueprint->add('brand_primary_color', null);
            $blueprint->add('brand_secondary_color', null);
            $blueprint->add('footer_about_text', null);
        });
    }
};
