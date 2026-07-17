<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Partner\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('logo')->maxLength(255),
            TextInput::make('url')->url()->maxLength(255),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_published')->default(false),
        ]);
    }
}
