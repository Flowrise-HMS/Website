<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\BookingRequest;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Core\Enums\NavigationGroup;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Pages\EditBookingRequest;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Pages\ListBookingRequests;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Schemas\BookingRequestForm;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Tables\BookingRequestsTable;
use Modules\Website\Filament\Clusters\Website\WebsiteCluster;
use Modules\Website\Models\BookingRequest;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Booking Requests';

    protected static ?int $navigationSort = 65;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return BookingRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookingRequests::route('/'),
            'edit' => EditBookingRequest::route('/{record}/edit'),
        ];
    }
}
