<?php

namespace Modules\Website\Classes\Support;

use Modules\Website\Settings\WebsiteSettings;
use Nwidart\Modules\Facades\Module;

class Website
{
    public static function moduleEnabled(): bool
    {
        return Module::has('Website') && Module::isEnabled('Website');
    }

    public static function isPublicEnabled(): bool
    {
        if (! self::moduleEnabled()) {
            return false;
        }

        try {
            return app(WebsiteSettings::class)->website_enabled;
        } catch (\Throwable) {
            return false;
        }
    }

    public static function panelPath(): string
    {
        if (! self::isPublicEnabled()) {
            return '/';
        }

        try {
            $slug = trim(app(WebsiteSettings::class)->panel_path_slug, '/');
        } catch (\Throwable) {
            $slug = 'admin';
        }

        if ($slug === '' || ! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            $slug = 'admin';
        }

        return $slug;
    }

    public static function settings(): WebsiteSettings
    {
        return app(WebsiteSettings::class);
    }
}
