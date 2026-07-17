<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\ContactSubmissionResource;

class EditContactSubmission extends EditRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
