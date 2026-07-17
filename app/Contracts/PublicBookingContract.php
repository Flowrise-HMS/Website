<?php

namespace Modules\Website\Contracts;

interface PublicBookingContract
{
    public function isAvailable(): bool;

    /**
     * @return list<array{id: string, name: string, duration_minutes: ?int}>
     */
    public function bookableServices(): array;

    /**
     * @return list<array{starts_at: string, ends_at: string, label: string}>
     */
    public function availableSlots(string $serviceId, ?string $fromDate = null): array;

    /**
     * @param  array{patient_id: string, service_id: string, starts_at: string, ends_at: string, reason_text?: ?string}  $data
     * @return array{type: string, reference: string, starts_at: string, ends_at: string}
     */
    public function bookSlot(array $data): array;

    /**
     * @param  array{patient_id: string, service_id: string, notes?: ?string}  $data
     * @return array{type: string, reference: string}
     */
    public function joinWaitlist(array $data): array;

    /**
     * @param  array{patient_id: string, service_id: string, preferred_starts_at: string, preferred_ends_at?: ?string, notes?: ?string}  $data
     * @return array{type: string, reference: string}
     */
    public function submitPreferredRequest(array $data): array;
}
