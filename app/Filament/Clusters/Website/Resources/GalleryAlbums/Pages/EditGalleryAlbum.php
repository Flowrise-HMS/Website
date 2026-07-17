<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\GalleryAlbumResource;

class EditGalleryAlbum extends EditRecord
{
    protected static string $resource = GalleryAlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
