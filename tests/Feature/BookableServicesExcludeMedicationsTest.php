<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Core\Enums\ServiceCategoryCode;
use Modules\Core\Models\Service;
use Modules\Core\Models\ServiceCategory;
use Modules\Website\Classes\Support\AppointmentPublicBooking;
use Modules\Website\Settings\WebsiteSettings;
use Modules\Website\Tests\Concerns\SeedsWebsiteSettings;
use Tests\TestCase;

/**
 * Pharmacy creates a Core service per medication; those must never surface
 * as bookable appointment services on the public website.
 */
class BookableServicesExcludeMedicationsTest extends TestCase
{
    use DatabaseTransactions;
    use SeedsWebsiteSettings;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requireModule('Website');
        $this->migrateModules(['Website', 'Patient', 'Appointment']);
        $this->seedWebsiteSettings();
    }

    public function test_public_booking_ignores_medication_services_even_when_selected(): void
    {
        $consultCategory = ServiceCategory::query()->firstOrCreate(['code' => 'CON'], ['name' => 'Consultation', 'is_active' => true]);
        $medicationCategory = ServiceCategory::query()->firstOrCreate(['code' => ServiceCategoryCode::MED->value], ['name' => 'Medications', 'is_active' => true]);

        $consultation = Service::factory()->forCategory($consultCategory)->create(['name' => 'General consultation', 'is_active' => true]);
        $paracetamol = Service::factory()->forCategory($medicationCategory)->create(['name' => 'Paracetamol 500mg', 'is_active' => true]);

        $settings = app(WebsiteSettings::class);
        $settings->bookable_service_ids = [$consultation->id, $paracetamol->id];
        $settings->save();

        $names = collect(app(AppointmentPublicBooking::class)->bookableServices())->pluck('name')->all();

        $this->assertContains('General consultation', $names);
        $this->assertNotContains('Paracetamol 500mg', $names);
    }
}
