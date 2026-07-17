<?php

namespace Modules\Website\Classes\Support;

use Modules\Website\Contracts\PublicBookingContract;
use RuntimeException;

class NullPublicBooking implements PublicBookingContract
{
    public function isAvailable(): bool
    {
        return false;
    }

    public function bookableServices(): array
    {
        return [];
    }

    public function availableSlots(string $serviceId, ?string $fromDate = null): array
    {
        return [];
    }

    public function bookSlot(array $data): array
    {
        throw new RuntimeException('Online booking is unavailable.');
    }

    public function joinWaitlist(array $data): array
    {
        throw new RuntimeException('Online booking is unavailable.');
    }

    public function submitPreferredRequest(array $data): array
    {
        throw new RuntimeException('Online booking is unavailable.');
    }
}
