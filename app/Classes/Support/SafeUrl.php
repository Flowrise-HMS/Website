<?php

namespace Modules\Website\Classes\Support;

use Illuminate\Support\Facades\Storage;

final class SafeUrl
{
    /**
     * Sanitize a CMS or user-provided URL for use in href attributes.
     */
    public static function href(?string $url, string $fallback = '#'): string
    {
        if (! is_string($url)) {
            return $fallback;
        }

        $url = trim($url);

        if ($url === '' || $url === '#') {
            return $url === '#' ? '#' : $fallback;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        if (in_array($scheme, ['http', 'https', 'mailto', 'tel'], true)) {
            return $url;
        }

        return $fallback;
    }

    /**
     * Resolve a stored media path for img src / lightbox href attributes.
     */
    public static function media(?string $path, string $fallback): string
    {
        if (! is_string($path) || trim($path) === '') {
            return $fallback;
        }

        $path = trim($path);

        if (preg_match('#^(https?)://#i', $path) === 1) {
            return $path;
        }

        if (str_starts_with($path, '/') && ! str_starts_with($path, '//')) {
            return $path;
        }

        $scheme = strtolower((string) parse_url($path, PHP_URL_SCHEME));
        if ($scheme !== '' && ! in_array($scheme, ['http', 'https'], true)) {
            return $fallback;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset($path);
    }
}
