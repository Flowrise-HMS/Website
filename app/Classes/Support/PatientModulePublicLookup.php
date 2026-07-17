<?php

namespace Modules\Website\Classes\Support;

use Modules\Core\Support\ModuleAvailability;
use Modules\Patient\Classes\Services\PatientSearchService;
use Modules\Patient\Classes\Services\PatientService;
use Modules\Patient\Enums\IdentifierType;
use Modules\Patient\Models\Patient;
use Modules\Website\Contracts\PatientPublicLookupContract;
use RuntimeException;

class PatientModulePublicLookup implements PatientPublicLookupContract
{
    public function __construct(
        protected PatientSearchService $searchService,
        protected PatientService $patientService,
    ) {}

    public function isAvailable(): bool
    {
        return ModuleAvailability::patientEnabled();
    }

    public function search(string $identifier): array
    {
        $term = trim($identifier);
        if ($term === '') {
            return [];
        }

        $patients = collect();

        $byMrn = $this->searchService->searchExactMrn($term);
        if ($byMrn) {
            $patients->push($byMrn);
        }

        $patients = $patients
            ->merge($this->searchService->searchByPhone($term))
            ->merge(Patient::query()->where('email', $term)->limit(5)->get())
            ->merge(
                Patient::query()
                    ->whereHas('identifiers', function ($query) use ($term): void {
                        $query->where('type', IdentifierType::NATIONAL_ID->value)
                            ->where('value', $term);
                    })
                    ->limit(5)
                    ->get()
            )
            ->unique('id')
            ->take(5);

        return $patients->map(fn (Patient $patient): array => [
            'id' => (string) $patient->id,
            'masked_name' => $this->maskName($patient->first_name, $patient->last_name),
            'has_email' => filled($patient->email),
            'has_phone' => filled($patient->phone),
        ])->values()->all();
    }

    public function reveal(string $patientId): ?array
    {
        $patient = Patient::query()->find($patientId);
        if (! $patient) {
            return null;
        }

        return $this->toPublicArray($patient);
    }

    public function createMinimal(array $data): array
    {
        if (! $this->isAvailable()) {
            throw new RuntimeException('Patient module is not available for public booking.');
        }

        $payload = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'branch_id' => $data['branch_id'] ?? null,
            'is_active' => true,
        ];

        $patient = $this->patientService->create($payload);

        if (filled($data['national_id'] ?? null)) {
            $patient->identifiers()->create([
                'type' => IdentifierType::NATIONAL_ID->value,
                'value' => $data['national_id'],
                'is_primary' => true,
            ]);
        }

        return $this->toPublicArray($patient->fresh());
    }

    /**
     * @return array{id: string, name: string, email: ?string, phone: ?string, mrn: ?string}
     */
    protected function toPublicArray(Patient $patient): array
    {
        return [
            'id' => (string) $patient->id,
            'name' => trim($patient->first_name.' '.$patient->last_name),
            'email' => $patient->email,
            'phone' => $patient->phone,
            'mrn' => $patient->mrn,
        ];
    }

    protected function maskName(?string $first, ?string $last): string
    {
        $mask = static function (?string $part): string {
            $part = trim((string) $part);
            if ($part === '') {
                return '***';
            }

            return mb_substr($part, 0, 1).str_repeat('*', max(mb_strlen($part) - 1, 2));
        };

        return trim($mask($first).' '.$mask($last));
    }
}
