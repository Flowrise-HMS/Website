<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Partner;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Core\Enums\NavigationGroup;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Pages\CreatePartner;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Pages\EditPartner;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Pages\ListPartners;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Schemas\PartnerForm;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Tables\PartnersTable;
use Modules\Website\Filament\Clusters\Website\WebsiteCluster;
use Modules\Website\Models\Partner;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $navigationLabel = 'Partners';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return PartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartners::route('/'),
            'create' => CreatePartner::route('/create'),
            'edit' => EditPartner::route('/{record}/edit'),
        ];
    }
}
