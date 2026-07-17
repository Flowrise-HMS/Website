<?php

namespace Modules\Website\Contracts;

interface PatientPublicLookupContract
{
    public function isAvailable(): bool;

    /**
     * @return list<array{id: string, masked_name: string, has_email: bool, has_phone: bool}>
     */
    public function search(string $identifier): array;

    /**
     * @return array{id: string, name: string, email: ?string, phone: ?string, mrn: ?string}|null
     */
    public function reveal(string $patientId): ?array;

    /**
     * @param  array{first_name: string, last_name: string, phone?: ?string, email?: ?string, national_id?: ?string, branch_id?: ?string}  $data
     * @return array{id: string, name: string, email: ?string, phone: ?string, mrn: ?string}
     */
    public function createMinimal(array $data): array;
}
