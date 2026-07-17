<?php

namespace Modules\Website\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Modules\Core\Models\Service;
use Modules\Website\Classes\Services\BookingOtpService;
use Modules\Website\Classes\Support\BookingContactNotifiable;
use Modules\Website\Contracts\BookingCtaResolver;
use Modules\Website\Contracts\PatientPublicLookupContract;
use Modules\Website\Contracts\PublicBookingContract;
use Modules\Website\Notifications\BookingConfirmationNotification;
use Modules\Website\Settings\WebsiteSettings;

class BookingWizard extends Component
{
    public string $step = 'service';

    public string $serviceId = '';

    public string $identifier = '';

    /** @var list<array{id: string, masked_name: string, has_email: bool, has_phone: bool}> */
    public array $matches = [];

    public ?string $selectedPatientId = null;

    public string $challengeId = '';

    public string $otp = '';

    public string $guestFirstName = '';

    public string $guestLastName = '';

    public string $guestPhone = '';

    public string $guestEmail = '';

    public string $guestNationalId = '';

    #[Locked]
    public ?string $patientId = null;

    #[Locked]
    public string $patientName = '';

    #[Locked]
    public ?string $patientEmail = null;

    #[Locked]
    public ?string $patientPhone = null;

    #[Locked]
    public bool $identityVerified = false;

    public string $selectedSlotStartsAt = '';

    public string $selectedSlotEndsAt = '';

    public string $fallbackMode = '';

    public string $preferredStartsAt = '';

    public string $notes = '';

    public bool $completed = false;

    public string $resultMessage = '';

    public string $resultReference = '';

    public function mount(): void
    {
        //
    }

    public function selectService(string $serviceId): void
    {
        $this->resetVerifiedIdentity();
        $this->serviceId = $serviceId;
        $this->step = 'identity';
    }

    public function searchPatient(PatientPublicLookupContract $lookup): void
    {
        $this->validate([
            'identifier' => ['required', 'string', 'max:120'],
        ]);

        if (! $lookup->isAvailable()) {
            $this->addError('identifier', __('Patient lookup is unavailable. Continue as a guest.'));

            return;
        }

        $this->matches = $lookup->search($this->identifier);
        $this->selectedPatientId = null;
    }

    public function chooseMatch(string $patientId, BookingOtpService $otp, PatientPublicLookupContract $lookup): void
    {
        $this->selectedPatientId = $patientId;
        $revealed = $lookup->reveal($patientId);

        if ($revealed === null) {
            $this->addError('identifier', __('Patient could not be found.'));

            return;
        }

        try {
            $sent = $otp->send([
                'email' => $revealed['email'],
                'phone' => $revealed['phone'],
                'patient_id' => $patientId,
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->errors());

            return;
        }

        $this->challengeId = $sent['challenge_id'];
        $this->step = 'otp';
    }

    public function verifyOtp(BookingOtpService $otp, PatientPublicLookupContract $lookup): void
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
            'challengeId' => ['required', 'string'],
        ]);

        try {
            $payload = $otp->verify($this->challengeId, $this->otp);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->errors());

            return;
        }

        $patientId = $payload['patient_id'] ?? $this->selectedPatientId;
        if (! filled($patientId)) {
            $this->addError('otp', __('Verification failed.'));

            return;
        }

        $revealed = $lookup->reveal((string) $patientId);
        if ($revealed === null) {
            $this->addError('otp', __('Patient could not be found.'));

            return;
        }

        $this->patientId = $revealed['id'];
        $this->patientName = $revealed['name'];
        $this->patientEmail = $revealed['email'];
        $this->patientPhone = $revealed['phone'];
        $this->identityVerified = true;
        $this->step = 'slots';
    }

    public function continueAsGuest(): void
    {
        $this->resetVerifiedIdentity();
        $this->selectedPatientId = null;
        $this->matches = [];
        $this->step = 'guest';
    }

    public function submitGuest(PatientPublicLookupContract $lookup, WebsiteSettings $settings): void
    {
        $this->validate([
            'guestFirstName' => ['required', 'string', 'max:100'],
            'guestLastName' => ['required', 'string', 'max:100'],
            'guestPhone' => ['nullable', 'string', 'max:50'],
            'guestEmail' => ['nullable', 'email', 'max:255'],
            'guestNationalId' => ['nullable', 'string', 'max:100'],
        ]);

        if (! filled($this->guestPhone) && ! filled($this->guestEmail)) {
            $this->addError('guestPhone', __('Provide a phone number or email so we can contact you.'));

            return;
        }

        try {
            $created = $lookup->createMinimal([
                'first_name' => $this->guestFirstName,
                'last_name' => $this->guestLastName,
                'phone' => $this->guestPhone ?: null,
                'email' => $this->guestEmail ?: null,
                'national_id' => $this->guestNationalId ?: null,
                'branch_id' => $settings->booking_branch_id,
            ]);
        } catch (\Throwable $e) {
            $this->addError('guestFirstName', $e->getMessage());

            return;
        }

        $this->patientId = $created['id'];
        $this->patientName = $created['name'];
        $this->patientEmail = $created['email'];
        $this->patientPhone = $created['phone'];
        $this->identityVerified = true;
        $this->step = 'slots';
    }

    public function selectSlot(string $startsAt, string $endsAt): void
    {
        $this->selectedSlotStartsAt = $startsAt;
        $this->selectedSlotEndsAt = $endsAt;
        $this->fallbackMode = '';
    }

    public function chooseWaitlist(): void
    {
        $this->fallbackMode = 'waitlist';
        $this->selectedSlotStartsAt = '';
        $this->selectedSlotEndsAt = '';
    }

    public function choosePreferred(): void
    {
        $this->fallbackMode = 'preferred';
        $this->selectedSlotStartsAt = '';
        $this->selectedSlotEndsAt = '';
    }

    public function confirmBooking(PublicBookingContract $booking): void
    {
        if (! $this->identityVerified || ! filled($this->patientId) || ! filled($this->serviceId)) {
            $this->addError('serviceId', __('Booking is incomplete. Verify your identity first.'));

            return;
        }

        try {
            if (filled($this->selectedSlotStartsAt) && filled($this->selectedSlotEndsAt)) {
                $result = $booking->bookSlot([
                    'patient_id' => $this->patientId,
                    'service_id' => $this->serviceId,
                    'starts_at' => $this->selectedSlotStartsAt,
                    'ends_at' => $this->selectedSlotEndsAt,
                    'reason_text' => $this->notes ?: null,
                ]);
                $message = __('Your appointment is confirmed.');
            } elseif ($this->fallbackMode === 'waitlist') {
                $result = $booking->joinWaitlist([
                    'patient_id' => $this->patientId,
                    'service_id' => $this->serviceId,
                    'notes' => $this->notes ?: null,
                ]);
                $message = __('You have been added to the waitlist. Our team will contact you.');
            } elseif ($this->fallbackMode === 'preferred') {
                $this->validate([
                    'preferredStartsAt' => ['required', 'date'],
                ]);
                $result = $booking->submitPreferredRequest([
                    'patient_id' => $this->patientId,
                    'service_id' => $this->serviceId,
                    'preferred_starts_at' => $this->preferredStartsAt,
                    'notes' => $this->notes ?: null,
                ]);
                $message = __('Your preferred time request was submitted for staff confirmation.');
            } else {
                $this->addError('selectedSlotStartsAt', __('Choose a slot, waitlist, or preferred time.'));

                return;
            }
        } catch (ValidationException $e) {
            $this->setErrorBag($e->errors());

            return;
        } catch (\Throwable $e) {
            $this->addError('serviceId', $e->getMessage());

            return;
        }

        $serviceName = Service::query()->find($this->serviceId)?->name;

        Notification::send(
            new BookingContactNotifiable($this->patientEmail, $this->patientPhone),
            new BookingConfirmationNotification([
                'type' => $result['type'],
                'reference' => $result['reference'] ?? null,
                'service' => $serviceName,
                'starts_at' => $result['starts_at'] ?? ($this->preferredStartsAt ?: null),
                'message' => $message,
            ])
        );

        $this->completed = true;
        $this->resultMessage = $message;
        $this->resultReference = $result['reference'] ?? '';
        $this->step = 'done';
    }

    protected function resetVerifiedIdentity(): void
    {
        $this->identityVerified = false;
        $this->patientId = null;
        $this->patientName = '';
        $this->patientEmail = null;
        $this->patientPhone = null;
        $this->challengeId = '';
        $this->otp = '';
        $this->selectedSlotStartsAt = '';
        $this->selectedSlotEndsAt = '';
        $this->fallbackMode = '';
        $this->preferredStartsAt = '';
    }

    public function render(PublicBookingContract $booking, BookingCtaResolver $cta): View
    {
        return view('website::livewire.booking-wizard', [
            'bookingAvailable' => $booking->isAvailable(),
            'services' => $booking->bookableServices(),
            'slots' => ($this->step === 'slots' && filled($this->serviceId))
                ? $booking->availableSlots($this->serviceId)
                : [],
            'cta' => $cta->resolve(),
        ]);
    }
}
