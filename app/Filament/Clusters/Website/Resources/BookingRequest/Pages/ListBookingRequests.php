<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\BookingRequestResource;

class ListBookingRequests extends ListRecords
{
    protected static string $resource = BookingRequestResource::class;
}
