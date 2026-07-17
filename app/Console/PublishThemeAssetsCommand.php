<?php

namespace Modules\Website\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Modules\Website\Classes\Support\ThemeManager;

class PublishThemeAssetsCommand extends Command
{
    protected $signature = 'website:publish-theme-assets {theme? : The theme to publish assets for}';

    protected $description = 'Publish website theme assets from the module to the public directory';

    public function handle(ThemeManager $themes): int
    {
        $themeArgument = $this->argument('theme');

        if (is_string($themeArgument) && $themeArgument !== '') {
            return $this->publishTheme($themes, $themeArgument) ? self::SUCCESS : self::FAILURE;
        }

        $published = 0;

        foreach (array_keys($themes->themeOptions()) as $theme) {
            if ($this->publishTheme($themes, $theme)) {
                $published++;
            }
        }

        if ($published === 0) {
            $this->warn('No theme assets were published.');

            return self::FAILURE;
        }

        $this->info("Published assets for {$published} theme(s).");

        return self::SUCCESS;
    }

    protected function publishTheme(ThemeManager $themes, string $theme): bool
    {
        $assetsSource = $themes->publishableAssetsPath($theme).'/assets';

        if (! is_dir($assetsSource)) {
            $this->warn("No assets found for theme [{$theme}] at {$assetsSource}");

            return false;
        }

        $destination = $themes->publicAssetsPath($theme);

        if (is_dir($destination)) {
            File::deleteDirectory($destination);
        }

        File::ensureDirectoryExists(dirname($destination));
        File::copyDirectory($assetsSource, $destination);
        $this->removeExecutableScripts($destination);

        $this->info("Published theme assets [{$theme}] to {$destination}");

        return true;
    }

    /**
     * Theme packs may include demo contact scripts; never expose them publicly.
     */
    protected function removeExecutableScripts(string $directory): void
    {
        foreach (File::allFiles($directory) as $file) {
            if (strtolower($file->getExtension()) === 'php') {
                File::delete($file->getPathname());
            }
        }
    }
}
