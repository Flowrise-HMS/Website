<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use Modules\Website\Contracts\PublicBookingContract;
use Modules\Website\Livewire\BookingWizard;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

class BookingWizardIdentityTest extends TestCase
{
    use DatabaseTransactions;
    use SeedsWebsiteSettings;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requireModule('Website');
        $this->migrateModules(['Website']);
        $this->seedWebsiteSettings();
    }

    public function test_confirm_booking_requires_verified_identity(): void
    {
        Notification::fake();

        $booked = false;
        $booking = $this->mock(PublicBookingContract::class, function ($mock) use (&$booked): void {
            $mock->shouldReceive('isAvailable')->andReturn(true);
            $mock->shouldReceive('bookableServices')->andReturn([]);
            $mock->shouldReceive('availableSlots')->andReturn([]);
            $mock->shouldReceive('bookSlot')->andReturnUsing(function () use (&$booked) {
                $booked = true;

                return ['type' => 'appointment', 'reference' => 'X'];
            });
            $mock->shouldReceive('joinWaitlist')->andReturnUsing(function () use (&$booked) {
                $booked = true;

                return ['type' => 'waitlist', 'reference' => 'X'];
            });
            $mock->shouldReceive('submitPreferredRequest')->andReturnUsing(function () use (&$booked) {
                $booked = true;

                return ['type' => 'preferred', 'reference' => 'X'];
            });
        });
        $this->app->instance(PublicBookingContract::class, $booking);

        Livewire::test(BookingWizard::class)
            ->set('serviceId', 'svc-1')
            ->set('step', 'slots')
            ->set('selectedSlotStartsAt', now()->addDay()->toIso8601String())
            ->set('selectedSlotEndsAt', now()->addDay()->addHour()->toIso8601String())
            ->call('confirmBooking')
            ->assertHasErrors('serviceId')
            ->assertSet('completed', false)
            ->assertSet('identityVerified', false);

        $this->assertFalse($booked);
    }

    public function test_patient_id_property_is_locked_from_client(): void
    {
        $this->expectException(CannotUpdateLockedPropertyException::class);

        Livewire::test(BookingWizard::class)
            ->set('patientId', 'tampered-patient-id');
    }
}
