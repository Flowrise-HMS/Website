<?php

namespace Modules\Website\Classes\Support;

use Illuminate\Support\Facades\Storage;

class ThemeBranding
{
    public function __construct(
        private ThemeManager $themes,
    ) {}

    public function logoDarkUrl(): string
    {
        return $this->storedAssetUrl($this->setting('brand_logo_path'))
            ?? $this->themeAsset('logo_dark', 'images/logo-dark.png');
    }

    public function logoLightUrl(): string
    {
        return $this->storedAssetUrl($this->setting('brand_logo_light_path'))
            ?? $this->themeAsset('logo_light', 'images/logo-light.png');
    }

    public function faviconUrl(): string
    {
        return $this->storedAssetUrl($this->setting('brand_favicon_path'))
            ?? $this->themeAsset('favicon', 'images/favicons/favicon-32x32.png');
    }

    public function primaryColor(): ?string
    {
        $color = $this->setting('brand_primary_color');

        return is_string($color) && $color !== '' ? $color : null;
    }

    public function secondaryColor(): ?string
    {
        $color = $this->setting('brand_secondary_color');

        return is_string($color) && $color !== '' ? $color : null;
    }

    public function footerAboutText(): ?string
    {
        $text = $this->setting('footer_about_text');

        return is_string($text) && $text !== '' ? $text : null;
    }

    /**
     * @return array<string, string>
     */
    public function cssVariables(): array
    {
        $variables = [];

        if ($primary = $this->primaryColor()) {
            $variables['--website-brand-primary'] = $primary;
        }

        if ($secondary = $this->secondaryColor()) {
            $variables['--website-brand-secondary'] = $secondary;
        }

        return $variables;
    }

    public function cssVariablesStyle(): string
    {
        $variables = $this->cssVariables();

        if ($variables === []) {
            return '';
        }

        $rules = collect($variables)
            ->map(fn (string $value, string $name): string => "{$name}: {$value};")
            ->implode(' ');

        return ":root { {$rules} }";
    }

    private function setting(string $property): mixed
    {
        try {
            $settings = Website::settings();

            return $settings->{$property} ?? null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function storedAssetUrl(?string $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return asset(ltrim($path, '/'));
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset('storage/'.$path);
    }

    private function themeAsset(string $key, string $fallback): string
    {
        $manifest = $this->themes->themeManifest();
        $path = is_array($manifest) ? ($manifest['assets'][$key] ?? null) : null;

        if (is_string($path) && $path !== '') {
            return $this->themes->assetUrl($path);
        }

        return $this->themes->assetUrl($fallback);
    }
}
