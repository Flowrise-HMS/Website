<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Menus\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('location')->badge(),
                TextColumn::make('items_count')->counts('items')->label(__('Items')),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
