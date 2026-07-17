<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Menus\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\MenuResource;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
}
