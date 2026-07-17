<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('role')->maxLength(255),
            Textarea::make('bio')->rows(4)->columnSpanFull(),
            TextInput::make('photo')->maxLength(255),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_published')->default(false),
        ]);
    }
}
