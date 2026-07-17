<?php

namespace Modules\Website\Classes\Support;

use Modules\Website\Contracts\PatientPublicLookupContract;
use RuntimeException;

class NullPatientPublicLookup implements PatientPublicLookupContract
{
    public function isAvailable(): bool
    {
        return false;
    }

    public function search(string $identifier): array
    {
        return [];
    }

    public function reveal(string $patientId): ?array
    {
        return null;
    }

    public function createMinimal(array $data): array
    {
        throw new RuntimeException('Patient module is not available for public booking.');
    }
}
