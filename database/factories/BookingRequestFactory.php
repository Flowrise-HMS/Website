<?php

namespace Modules\Website\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Website\Enums\BookingRequestStatus;
use Modules\Website\Enums\BookingRequestType;
use Modules\Website\Models\BookingRequest;

/**
 * @extends Factory<BookingRequest>
 */
class BookingRequestFactory extends Factory
{
    protected $model = BookingRequest::class;

    public function definition(): array
    {
        return [
            'patient_id' => fake()->uuid(),
            'service_id' => fake()->uuid(),
            'branch_id' => fake()->uuid(),
            'type' => BookingRequestType::PreferredTime,
            'status' => BookingRequestStatus::Pending,
            'preferred_starts_at' => now()->addDays(2)->setTime(10, 0),
            'preferred_ends_at' => now()->addDays(2)->setTime(10, 30),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
