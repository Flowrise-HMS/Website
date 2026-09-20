<?php

namespace Modules\Website\Classes\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Appointment\Classes\Services\AppointmentSchedulingService;
use Modules\Appointment\Enums\AppointmentStatus;
use Modules\Appointment\Enums\AppointmentType;
use Modules\Appointment\Enums\WaitlistEntryStatus;
use Modules\Appointment\Models\WaitlistEntry;
use Modules\Core\Models\Service;
use Modules\Core\Support\ModuleAvailability;
use Modules\Website\Classes\Services\BookingSlotGenerator;
use Modules\Website\Contracts\PublicBookingContract;
use Modules\Website\Enums\BookingRequestStatus;
use Modules\Website\Enums\BookingRequestType;
use Modules\Website\Models\BookingRequest;
use Modules\Website\Settings\WebsiteSettings;
use RuntimeException;

class AppointmentPublicBooking implements PublicBookingContract
{
    public function __construct(
        protected WebsiteSettings $settings,
        protected BookingSlotGenerator $slots,
        protected AppointmentSchedulingService $scheduling,
    ) {}

    public function isAvailable(): bool
    {
        return ModuleAvailability::appointmentEnabled()
            && filled($this->settings->booking_branch_id)
            && $this->settings->bookable_service_ids !== [];
    }

    public function bookableServices(): array
    {
        if ($this->settings->bookable_service_ids === []) {
            return [];
        }

        return Service::query()
            ->whereIn('id', $this->settings->bookable_service_ids)
            ->active()
            ->nonMedication()
            ->orderBy('name')
            ->get(['id', 'name', 'estimated_duration_minutes'])
            ->map(fn (Service $service): array => [
                'id' => (string) $service->id,
                'name' => $service->name,
                'duration_minutes' => $service->estimated_duration_minutes,
            ])
            ->all();
    }

    public function availableSlots(string $serviceId, ?string $fromDate = null): array
    {
        $from = $fromDate ? Carbon::parse($fromDate) : null;

        return $this->slots->forService($serviceId, $from);
    }

    public function bookSlot(array $data): array
    {
        $this->assertAvailable();

        $branchId = $this->settings->booking_branch_id;
        $startsAt = Carbon::parse($data['starts_at']);
        $endsAt = Carbon::parse($data['ends_at']);

        $openSlots = collect($this->availableSlots($data['service_id']));
        $match = $openSlots->first(function (array $slot) use ($startsAt, $endsAt): bool {
            return Carbon::parse($slot['starts_at'])->equalTo($startsAt)
                && Carbon::parse($slot['ends_at'])->equalTo($endsAt);
        });

        if ($match === null) {
            throw ValidationException::withMessages([
                'slot' => __('That time is no longer available. Please choose another slot.'),
            ]);
        }

        $appointment = $this->scheduling->schedule([
            'branch_id' => $branchId,
            'patient_id' => $data['patient_id'],
            'service_id' => $data['service_id'],
            'status' => AppointmentStatus::BOOKED,
            'appointment_type' => AppointmentType::OUTPATIENT,
            'reason_text' => $data['reason_text'] ?? null,
            'start_at' => $startsAt,
            'end_at' => $endsAt,
            'idempotency_key' => 'website-booking-'.hash('sha256', implode('|', [
                $data['patient_id'],
                $data['service_id'],
                $startsAt->toIso8601String(),
            ])),
        ]);

        return [
            'type' => 'appointment',
            'reference' => (string) $appointment->id,
            'starts_at' => $appointment->start_at->toIso8601String(),
            'ends_at' => $appointment->end_at->toIso8601String(),
        ];
    }

    public function joinWaitlist(array $data): array
    {
        $this->assertAvailable();

        return DB::transaction(function () use ($data): array {
            $entry = WaitlistEntry::query()->create([
                'branch_id' => $this->settings->booking_branch_id,
                'patient_id' => $data['patient_id'],
                'status' => WaitlistEntryStatus::WAITING,
                'urgency_score' => 1,
                'wait_time_score' => 1,
                'referral_score' => 1,
                'manual_override_score' => 0,
                'computed_priority_score' => 3,
            ]);

            $request = BookingRequest::query()->create([
                'patient_id' => $data['patient_id'],
                'service_id' => $data['service_id'],
                'branch_id' => $this->settings->booking_branch_id,
                'type' => BookingRequestType::Waitlist,
                'status' => BookingRequestStatus::Pending,
                'notes' => $data['notes'] ?? null,
                'waitlist_entry_id' => $entry->id,
            ]);

            return [
                'type' => 'waitlist',
                'reference' => (string) $request->id,
            ];
        });
    }

    public function submitPreferredRequest(array $data): array
    {
        $this->assertAvailable();

        $starts = Carbon::parse($data['preferred_starts_at']);
        $ends = isset($data['preferred_ends_at'])
            ? Carbon::parse($data['preferred_ends_at'])
            : $starts->copy()->addMinutes(max(5, (int) $this->settings->booking_slot_minutes));

        $request = BookingRequest::query()->create([
            'patient_id' => $data['patient_id'],
            'service_id' => $data['service_id'],
            'branch_id' => $this->settings->booking_branch_id,
            'type' => BookingRequestType::PreferredTime,
            'status' => BookingRequestStatus::Pending,
            'preferred_starts_at' => $starts,
            'preferred_ends_at' => $ends,
            'notes' => $data['notes'] ?? null,
        ]);

        return [
            'type' => 'preferred_request',
            'reference' => (string) $request->id,
        ];
    }

    protected function assertAvailable(): void
    {
        if (! $this->isAvailable()) {
            throw new RuntimeException('Online booking is unavailable.');
        }
    }
}
