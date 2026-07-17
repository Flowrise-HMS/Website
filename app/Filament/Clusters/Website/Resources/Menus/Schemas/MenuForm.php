<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Modules\Website\Enums\MenuItemType;
use Modules\Website\Models\Page;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            Select::make('location')
                ->options([
                    'primary' => 'Primary',
                    'footer' => 'Footer',
                ])
                ->required()
                ->unique(ignoreRecord: true),
            Repeater::make('items')
                ->relationship()
                ->reorderable('sort_order')
                ->schema([
                    TextInput::make('label')->required()->maxLength(255),
                    Select::make('type')
                        ->options(collect(MenuItemType::cases())->mapWithKeys(
                            fn (MenuItemType $type) => [$type->value => str($type->name)->headline()]
                        ))
                        ->required()
                        ->live(),
                    Select::make('page_id')
                        ->label(__('Page'))
                        ->options(fn () => Page::query()->orderBy('title')->pluck('title', 'id'))
                        ->searchable()
                        ->visible(fn ($get): bool => in_array($get('type'), [MenuItemType::Page->value, MenuItemType::Cta->value], true)),
                    TextInput::make('url')
                        ->maxLength(255)
                        ->visible(fn ($get): bool => $get('type') === MenuItemType::Url->value),
                    Toggle::make('is_visible')->default(true),
                    Toggle::make('open_in_new_tab')->default(false),
                ])
                ->columnSpanFull(),
        ]);
    }
}
