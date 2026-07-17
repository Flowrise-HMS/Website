<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Core\Enums\NavigationGroup;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages\CreateGalleryAlbum;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages\EditGalleryAlbum;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages\ListGalleryAlbums;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Schemas\GalleryAlbumForm;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Tables\GalleryAlbumsTable;
use Modules\Website\Filament\Clusters\Website\WebsiteCluster;
use Modules\Website\Models\GalleryAlbum;

class GalleryAlbumResource extends Resource
{
    protected static ?string $model = GalleryAlbum::class;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Gallery';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return GalleryAlbumForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryAlbumsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleryAlbums::route('/'),
            'create' => CreateGalleryAlbum::route('/create'),
            'edit' => EditGalleryAlbum::route('/{record}/edit'),
        ];
    }
}
