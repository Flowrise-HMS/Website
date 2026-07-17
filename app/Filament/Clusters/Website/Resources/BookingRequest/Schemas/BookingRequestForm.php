<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('patient_id')->disabled(),
            TextInput::make('service_id')->disabled(),
            TextInput::make('type')->disabled(),
            Select::make('status')
                ->options([
                    'pending' => __('Pending'),
                    'accepted' => __('Accepted'),
                    'declined' => __('Declined'),
                    'cancelled' => __('Cancelled'),
                ])
                ->required(),
            DateTimePicker::make('preferred_starts_at')->disabled(),
            DateTimePicker::make('preferred_ends_at')->disabled(),
            Textarea::make('notes')->disabled()->columnSpanFull(),
            TextInput::make('waitlist_entry_id')->disabled(),
            TextInput::make('appointment_id')->disabled(),
        ]);
    }
}
