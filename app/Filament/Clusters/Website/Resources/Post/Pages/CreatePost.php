<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Post\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Website\Filament\Clusters\Website\Resources\Post\PostResource;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
