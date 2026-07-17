<?php

namespace Modules\Website\Database\Seeders;

use Illuminate\Database\Seeder;

class WebsiteDatabaseSeeder extends Seeder
{
    /**
     * Seed the website module demo content.
     *
     * Prefer theme-specific seeders at install time:
     *   php artisan website:seed-theme clinicalmaster --publish-assets
     *   php artisan db:seed --class="Modules\\Website\\Database\\Seeders\\Themes\\MedioxThemeSeeder"
     *
     * Optional env: WEBSITE_SEED_THEME=default|mediox|clinicalmaster
     */
    public function run(): void
    {
        $theme = (string) env('WEBSITE_SEED_THEME', 'default');
        $seederClass = ThemeSeederRegistry::resolve($theme);

        $seeder = $this->resolve($seederClass);

        if (method_exists($seeder, 'setFresh') && filter_var(env('WEBSITE_SEED_FRESH', false), FILTER_VALIDATE_BOOL)) {
            $seeder->setFresh(true);
        }

        $seeder->run();
    }
}
