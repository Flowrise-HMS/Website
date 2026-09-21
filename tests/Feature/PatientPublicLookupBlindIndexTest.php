<?php

namespace Modules\Website\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Patient\Enums\IdentifierType;
use Modules\Patient\Models\Patient;
use Modules\Patient\Models\PatientIdentifier;
use Modules\Website\Contracts\PatientPublicLookupContract;
use Tests\TestCase;

class PatientPublicLookupBlindIndexTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requireModule('Website');
        $this->requireModule('Patient');
        $this->migrateModules(['Core', 'Website', 'Patient']);
    }

    public function test_public_lookup_finds_a_patient_by_phone_email_or_national_id(): void
    {
        $patient = Patient::factory()->create(['phone' => '0244123456', 'email' => 'ama@example.com']);
        PatientIdentifier::factory()->create(['patient_id' => $patient->id, 'type' => IdentifierType::NATIONAL_ID->value, 'value' => 'GHA-123456789-0']);
        Patient::factory()->create(['phone' => '0244999999', 'email' => 'kofi@example.com']);

        $lookup = app(PatientPublicLookupContract::class);

        foreach (['+233 244 123 456', 'Ama@Example.com', 'gha-123456789-0'] as $term) {
            $matches = $lookup->search($term);

            $this->assertCount(1, $matches, "Term {$term}");
            $this->assertSame((string) $patient->id, $matches[0]['id'], "Term {$term}");
            $this->assertTrue($matches[0]['has_email']);
        }

        $this->assertSame([], $lookup->search('123456'));
    }
}
