<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\ContactSubmissionResource;

class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;
}
