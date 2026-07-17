<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Post\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
            Textarea::make('excerpt')->rows(2)->columnSpanFull(),
            RichEditor::make('body')->columnSpanFull(),
            TextInput::make('cover_image')->maxLength(255),
            DateTimePicker::make('published_at'),
            TextInput::make('meta_title')->maxLength(255),
            Textarea::make('meta_description')->rows(2)->columnSpanFull(),
        ]);
    }
}
