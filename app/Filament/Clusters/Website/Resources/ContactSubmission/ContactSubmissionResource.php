<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Core\Enums\NavigationGroup;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Pages\EditContactSubmission;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Pages\ListContactSubmissions;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Schemas\ContactSubmissionForm;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Tables\ContactSubmissionsTable;
use Modules\Website\Filament\Clusters\Website\WebsiteCluster;
use Modules\Website\Models\ContactSubmission;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?string $navigationLabel = 'Contact Inbox';

    protected static ?int $navigationSort = 70;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return ContactSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactSubmissionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactSubmissions::route('/'),
            'edit' => EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
