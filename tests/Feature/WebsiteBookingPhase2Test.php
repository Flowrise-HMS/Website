<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Service;
use Modules\Patient\Models\Patient;
use Modules\Website\Classes\Services\BookingOtpService;
use Modules\Website\Classes\Services\BookingSlotGenerator;
use Modules\Website\Classes\Support\BookingContactNotifiable;
use Modules\Website\Contracts\PatientPublicLookupContract;
use Modules\Website\Contracts\PublicBookingContract;
use Modules\Website\Enums\BookingRequestType;
use Modules\Website\Models\BookingRequest;
use Modules\Website\Notifications\BookingOtpNotification;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

class WebsiteBookingPhase2Test extends TestCase
{
    use DatabaseTransactions;
    use SeedsWebsiteSettings;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requireModule('Website');
        $this->requireModule('Patient');
        $this->requireModule('Appointment');
        $this->migrateModules(['Website', 'Patient', 'Appointment']);
        $this->seedWebsiteSettings();
    }

    public function test_booking_notifications_skip_channels_without_contact_details(): void
    {
        $notification = new BookingOtpNotification('123456');

        $this->assertSame(['sms'], $notification->via(new BookingContactNotifiable(null, '+233244000000')));
        $this->assertSame(['mail'], $notification->via(new BookingContactNotifiable('patient@example.com', null)));
        $this->assertSame([], $notification->via(new BookingContactNotifiable(null, null)));
    }

    public function test_otp_notification_uses_mail_and_sms_channels(): void
    {
        $notifiable = new BookingContactNotifiable('patient@example.com', '+233244000000');
        $notification = new BookingOtpNotification('123456');

        $this->assertSame(['mail', 'sms'], $notification->via($notifiable));

        Notification::fake();

        $result = app(BookingOtpService::class)->send([
            'email' => 'patient@example.com',
            'phone' => '+233244000000',
            'patient_id' => 'test-patient',
        ]);

        $this->assertNotEmpty($result['challenge_id']);
        Notification::assertSentTo($notifiable, BookingOtpNotification::class);
    }

    public function test_otp_verify_marks_challenge_verified(): void
    {
        Notification::fake();
        $service = app(BookingOtpService::class);

        $sent = $service->send([
            'email' => 'patient@example.com',
            'phone' => null,
            'patient_id' => 'abc',
        ]);

        $payload = Cache::get('website.booking.otp.'.$sent['challenge_id']);
        $this->assertIsArray($payload);

        // Recover plaintext by brute from hash for test — instead store known code via rewrite.
        Cache::put('website.booking.otp.'.$sent['challenge_id'], [
            ...$payload,
            'hash' => hash('sha256', '123456'),
        ], 600);

        $verified = $service->verify($sent['challenge_id'], '123456');
        $this->assertTrue($verified['verified']);
        $this->assertSame('abc', $service->patientIdIfVerified($sent['challenge_id']));
    }

    public function test_patient_lookup_returns_masked_names(): void
    {
        $patient = Patient::factory()->create([
            'first_name' => 'Ama',
            'last_name' => 'Boateng',
            'mrn' => 'MRN-BOOK-1',
            'phone' => '+233244111111',
            'email' => 'ama@example.com',
        ]);

        $lookup = app(PatientPublicLookupContract::class);
        $matches = $lookup->search('MRN-BOOK-1');

        $this->assertNotEmpty($matches);
        $this->assertSame((string) $patient->id, $matches[0]['id']);
        $this->assertStringContainsString('*', $matches[0]['masked_name']);
        $this->assertStringNotContainsString('Boateng', $matches[0]['masked_name']);
    }

    public function test_slot_generator_returns_future_open_slots(): void
    {
        $branch = Branch::factory()->create();
        $service = Service::factory()->create([
            'is_active' => true,
            'estimated_duration_minutes' => 30,
        ]);

        $settings = app(WebsiteSettings::class);
        $settings->booking_branch_id = $branch->id;
        $settings->bookable_service_ids = [$service->id];
        $settings->booking_open_weekdays = [1, 2, 3, 4, 5, 6, 7];
        $settings->booking_open_time = '08:00';
        $settings->booking_close_time = '12:00';
        $settings->booking_slot_minutes = 30;
        $settings->booking_horizon_days = 3;
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);

        $slots = app(BookingSlotGenerator::class)->forService($service->id);

        $this->assertNotEmpty($slots);
        $this->assertArrayHasKey('starts_at', $slots[0]);
        $this->assertArrayHasKey('ends_at', $slots[0]);
    }

    public function test_preferred_request_is_persisted(): void
    {
        $branch = Branch::factory()->create();
        $service = Service::factory()->create(['is_active' => true]);
        $patient = Patient::factory()->create(['branch_id' => $branch->id]);

        $settings = app(WebsiteSettings::class);
        $settings->booking_branch_id = $branch->id;
        $settings->bookable_service_ids = [$service->id];
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        app()->forgetInstance(PublicBookingContract::class);

        $booking = app(PublicBookingContract::class);
        $this->assertTrue($booking->isAvailable());

        $result = $booking->submitPreferredRequest([
            'patient_id' => $patient->id,
            'service_id' => $service->id,
            'preferred_starts_at' => now()->addDays(2)->setTime(10, 0)->toDateTimeString(),
            'notes' => 'Morning preferred',
        ]);

        $this->assertSame('preferred_request', $result['type']);
        $this->assertDatabaseHas('website_booking_requests', [
            'id' => $result['reference'],
            'patient_id' => $patient->id,
            'type' => BookingRequestType::PreferredTime->value,
        ]);
        $this->assertSame(1, BookingRequest::query()->count());
    }

    public function test_confirmed_slot_booking_creates_appointment(): void
    {
        Notification::fake();

        $branch = Branch::factory()->create();
        $service = Service::factory()->create([
            'is_active' => true,
            'estimated_duration_minutes' => 30,
        ]);
        $patient = Patient::factory()->create([
            'branch_id' => $branch->id,
            'email' => 'book@example.com',
            'phone' => '+233200000001',
        ]);

        $settings = app(WebsiteSettings::class);
        $settings->booking_branch_id = $branch->id;
        $settings->bookable_service_ids = [$service->id];
        $settings->booking_open_weekdays = [1, 2, 3, 4, 5, 6, 7];
        $settings->booking_open_time = '08:00';
        $settings->booking_close_time = '17:00';
        $settings->booking_slot_minutes = 30;
        $settings->booking_horizon_days = 7;
        $settings->save();
        app()->forgetInstance(WebsiteSettings::class);
        app()->forgetInstance(PublicBookingContract::class);
        app()->forgetInstance(BookingSlotGenerator::class);

        $booking = app(PublicBookingContract::class);
        $slots = $booking->availableSlots($service->id);
        $this->assertNotEmpty($slots);

        $slot = $slots[0];
        $result = $booking->bookSlot([
            'patient_id' => $patient->id,
            'service_id' => $service->id,
            'starts_at' => $slot['starts_at'],
            'ends_at' => $slot['ends_at'],
        ]);

        $this->assertSame('appointment', $result['type']);
        $this->assertDatabaseHas('appointments', [
            'id' => $result['reference'],
            'patient_id' => $patient->id,
            'service_id' => $service->id,
            'branch_id' => $branch->id,
        ]);
    }
}
