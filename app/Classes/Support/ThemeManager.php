<?php

namespace Modules\Website\Classes\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ThemeManager
{
    public function activeTheme(): string
    {
        try {
            $theme = Website::settings()->active_theme;
        } catch (\Throwable) {
            $theme = 'default';
        }

        return $this->themeExists($theme) ? $theme : 'default';
    }

    public function themeExists(string $theme): bool
    {
        return is_dir($this->themePath($theme));
    }

    public function themePath(string $theme): string
    {
        return module_path('Website', 'resources/themes/'.$theme);
    }

    /**
     * @return array<string, string>
     */
    public function themeOptions(): array
    {
        $themesPath = module_path('Website', 'resources/themes');
        if (! is_dir($themesPath)) {
            return ['default' => 'Default'];
        }

        $options = [];
        foreach (File::directories($themesPath) as $directory) {
            $name = basename($directory);
            $label = $name;
            $manifest = $directory.'/theme.json';
            if (is_file($manifest)) {
                $data = json_decode((string) file_get_contents($manifest), true);
                $label = is_array($data) ? (string) ($data['label'] ?? $name) : $name;
            }
            $options[$name] = $label;
        }

        ksort($options);

        return $options !== [] ? $options : ['default' => 'Default'];
    }

    public function view(string $view, array $data = []): \Illuminate\Contracts\View\View
    {
        $theme = $this->activeTheme();
        $themed = "website::themes.{$theme}.{$view}";
        $fallback = "website::themes.default.{$view}";

        if (View::exists($themed)) {
            return view($themed, $data);
        }

        return view($fallback, $data);
    }

    public function sectionViewName(string $sectionType): string
    {
        $theme = $this->activeTheme();
        $themed = "website::themes.{$theme}.sections.{$sectionType}";
        $fallback = "website::themes.default.sections.{$sectionType}";

        return View::exists($themed) ? $themed : $fallback;
    }

    public function layoutView(): string
    {
        $theme = $this->activeTheme();
        $themed = "website::themes.{$theme}.layouts.app";
        $fallback = 'website::themes.default.layouts.app';

        return View::exists($themed) ? $themed : $fallback;
    }

    public function assetUrl(string $path): string
    {
        $path = ltrim($path, '/');

        // Published under /site-themes (not /website) so assets never collide with the
        // Filament Website cluster route at /{panel}/website or /website when the panel is root.
        return asset('site-themes/'.$this->activeTheme().'/assets/'.$path);
    }

    public function publishableAssetsPath(string $theme): string
    {
        return module_path('Website', 'resources/assets/themes/'.$theme);
    }

    public function publicAssetsPath(string $theme): string
    {
        return public_path('site-themes/'.$theme.'/assets');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function themeManifest(?string $theme = null): ?array
    {
        $theme ??= $this->activeTheme();
        $manifest = $this->themePath($theme).'/theme.json';

        if (! is_file($manifest)) {
            return null;
        }

        $data = json_decode((string) file_get_contents($manifest), true);

        return is_array($data) ? $data : null;
    }

    /**
     * @return array<string, string>
     */
    public function layouts(?string $theme = null): array
    {
        $theme ??= $this->activeTheme();
        $manifest = $this->themeManifest($theme);
        $layouts = $manifest['layouts'] ?? [];

        if (! is_array($layouts) || $layouts === []) {
            return [];
        }

        $options = [];
        foreach ($layouts as $key => $label) {
            if (is_int($key) && is_string($label)) {
                $options[$label] = str($label)->headline()->toString();

                continue;
            }

            if (is_string($key) && (is_string($label) || is_numeric($label))) {
                $options[$key] = (string) $label;
            }
        }

        return $options;
    }

    public function defaultLayout(?string $theme = null): ?string
    {
        $theme ??= $this->activeTheme();
        $manifest = $this->themeManifest($theme);
        $default = $manifest['default_layout'] ?? null;

        if (is_string($default) && $default !== '' && array_key_exists($default, $this->layouts($theme))) {
            return $default;
        }

        $layouts = $this->layouts($theme);

        return $layouts === [] ? null : array_key_first($layouts);
    }

    public function resolveLayout(?string $layout, ?string $theme = null): ?string
    {
        $theme ??= $this->activeTheme();
        $layouts = $this->layouts($theme);

        if ($layouts === []) {
            return null;
        }

        if (is_string($layout) && $layout !== '' && array_key_exists($layout, $layouts)) {
            return $layout;
        }

        return $this->defaultLayout($theme);
    }

    public function pageViewName(?string $layout = null): string
    {
        $theme = $this->activeTheme();
        $resolved = $this->resolveLayout($layout, $theme);

        if ($resolved !== null) {
            $themedLayout = "website::themes.{$theme}.pages.layouts.{$resolved}";
            if (View::exists($themedLayout)) {
                return $themedLayout;
            }
        }

        $themed = "website::themes.{$theme}.pages.show";
        $fallback = 'website::themes.default.pages.show';

        return View::exists($themed) ? $themed : $fallback;
    }

    public function registerViewNamespaces(): void
    {
        // Themes are under resources/views/themes via module views.
    }
}
