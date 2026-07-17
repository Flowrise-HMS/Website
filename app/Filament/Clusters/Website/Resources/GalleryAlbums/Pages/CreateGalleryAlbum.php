<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\GalleryAlbumResource;

class CreateGalleryAlbum extends CreateRecord
{
    protected static string $resource = GalleryAlbumResource::class;
}
