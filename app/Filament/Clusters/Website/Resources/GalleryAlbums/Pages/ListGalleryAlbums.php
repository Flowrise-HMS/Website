<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\GalleryAlbumResource;

class ListGalleryAlbums extends ListRecords
{
    protected static string $resource = GalleryAlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
