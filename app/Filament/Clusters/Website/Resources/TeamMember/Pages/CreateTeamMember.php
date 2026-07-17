<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\TeamMemberResource;

class CreateTeamMember extends CreateRecord
{
    protected static string $resource = TeamMemberResource::class;
}
