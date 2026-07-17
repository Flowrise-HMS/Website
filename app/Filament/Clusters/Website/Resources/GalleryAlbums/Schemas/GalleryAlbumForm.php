<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GalleryAlbumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Textarea::make('description')->rows(3)->columnSpanFull(),
            FileUpload::make('cover_image')
                ->label(__('Cover image'))
                ->image()
                ->disk('public')
                ->directory('website/gallery')
                ->visibility('public')
                ->maxSize(4096),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_published')->default(false),
            Repeater::make('items')
                ->relationship()
                ->reorderable('sort_order')
                ->schema([
                    TextInput::make('title'),
                    FileUpload::make('image_path')
                        ->label(__('Image'))
                        ->image()
                        ->required()
                        ->disk('public')
                        ->directory('website/gallery')
                        ->visibility('public')
                        ->maxSize(4096),
                    TextInput::make('alt_text'),
                ])
                ->columnSpanFull(),
        ]);
    }
}
