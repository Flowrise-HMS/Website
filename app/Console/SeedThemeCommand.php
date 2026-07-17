<?php

namespace Modules\Website\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Database\Seeders\ThemeSeederRegistry;
use Modules\Website\Settings\WebsiteSettings;

class SeedThemeCommand extends Command
{
    protected $signature = 'website:seed-theme
        {theme? : Theme key (default, mediox, clinicalmaster)}
        {--fresh : Replace existing demo pages, sections, menu, team, partners, and posts}
        {--publish-assets : Publish the theme assets to public/site-themes}
        {--enable : Enable the public website and keep Filament under panel_path_slug}';

    protected $description = 'Seed demo CMS content for a specific website theme pack';

    public function handle(ThemeManager $themes): int
    {
        $theme = $this->argument('theme');

        if (! is_string($theme) || $theme === '') {
            $choices = ThemeSeederRegistry::keys();
            $theme = $this->choice('Which theme should be seeded?', $choices, 0);
        }

        $theme = strtolower(trim($theme));

        try {
            $seederClass = ThemeSeederRegistry::resolve($theme);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if (! $themes->themeExists($theme)) {
            $this->error("Theme files for [{$theme}] were not found under resources/themes.");

            return self::FAILURE;
        }

        $this->info("Seeding website theme [{$theme}]…");

        $seeder = app($seederClass);
        if (method_exists($seeder, 'setFresh')) {
            $seeder->setFresh((bool) $this->option('fresh'));
        }

        $seeder->run();

        if ($this->option('enable')) {
            $settings = app(WebsiteSettings::class);
            $settings->website_enabled = true;
            if (blank($settings->panel_path_slug)) {
                $settings->panel_path_slug = 'admin';
            }
            $settings->save();
            app()->forgetInstance(WebsiteSettings::class);
            $this->info('Public website enabled.');
        }

        if ($this->option('publish-assets')) {
            $exit = Artisan::call('website:publish-theme-assets', ['theme' => $theme]);
            $this->output->write(Artisan::output());

            if ($exit !== self::SUCCESS) {
                $this->warn('Theme content was seeded, but asset publishing reported a problem.');
            }
        }

        $this->components->info("Theme [{$theme}] seeded via {$seederClass}");

        return self::SUCCESS;
    }
}
