<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryAlbumsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('slug'),
                IconColumn::make('is_published')->boolean(),
                TextColumn::make('items_count')->counts('items'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
