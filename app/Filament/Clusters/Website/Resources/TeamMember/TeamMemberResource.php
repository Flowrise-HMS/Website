<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\TeamMember;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Core\Enums\NavigationGroup;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Pages\CreateTeamMember;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Pages\EditTeamMember;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Pages\ListTeamMembers;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Schemas\TeamMemberForm;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Tables\TeamMembersTable;
use Modules\Website\Filament\Clusters\Website\WebsiteCluster;
use Modules\Website\Models\TeamMember;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Team';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return TeamMemberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamMembersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeamMembers::route('/'),
            'create' => CreateTeamMember::route('/create'),
            'edit' => EditTeamMember::route('/{record}/edit'),
        ];
    }
}
