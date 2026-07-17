<?php

use Illuminate\Support\Facades\Route;
use Modules\Website\Classes\Support\Website;
use Modules\Website\Http\Controllers\SiteController;

if (! Website::isPublicEnabled()) {
    return;
}

$panelSlug = trim((string) Website::panelPath(), '/');
$reserved = ['api', 'livewire', 'sanctum', 'storage', 'vendor', 'build', 'css', 'js', 'fonts'];
if ($panelSlug !== '' && $panelSlug !== '/') {
    $reserved[] = $panelSlug;
}
$reservedPattern = implode('|', array_map('preg_quote', $reserved));

Route::get('/', [SiteController::class, 'home'])->name('website.home');
Route::get('/news', [SiteController::class, 'newsIndex'])->name('website.news.index');
Route::get('/news/{slug}', [SiteController::class, 'newsShow'])->name('website.news.show');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('website.gallery');
Route::get('/team', [SiteController::class, 'team'])->name('website.team');
Route::get('/partners', [SiteController::class, 'partners'])->name('website.partners');
Route::get('/contact', [SiteController::class, 'contact'])->name('website.contact');
Route::get('/book-appointment', [SiteController::class, 'booking'])->name('website.booking');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('website.sitemap');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('website.robots');

// When the Filament panel is namespaced (e.g. /admin), send /website* to the admin
// Website cluster so it does not collide with the public CMS /{slug} catch-all.
if ($panelSlug !== '' && $panelSlug !== '/') {
    Route::redirect('/website', '/'.$panelSlug.'/website');
    Route::get('/website/{path}', function (string $path) use ($panelSlug) {
        return redirect('/'.$panelSlug.'/website/'.$path);
    })->where('path', '.*');
}

Route::get('/{slug}', [SiteController::class, 'page'])
    ->where('slug', "^(?!{$reservedPattern}$).+")
    ->name('website.page');
