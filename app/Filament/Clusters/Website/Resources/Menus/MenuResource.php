<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Menus;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Core\Enums\NavigationGroup;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Pages\CreateMenu;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Pages\EditMenu;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Pages\ListMenus;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Schemas\MenuForm;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Tables\MenusTable;
use Modules\Website\Filament\Clusters\Website\WebsiteCluster;
use Modules\Website\Models\Menu;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?string $navigationLabel = 'Menus';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
