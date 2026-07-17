<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Website\Database\Factories\BookingRequestFactory;
use Modules\Website\Enums\BookingRequestStatus;
use Modules\Website\Enums\BookingRequestType;

class BookingRequest extends Model
{
    /** @use HasFactory<BookingRequestFactory> */
    use HasFactory, HasUuids;

    protected $table = 'website_booking_requests';

    protected $fillable = [
        'patient_id',
        'service_id',
        'branch_id',
        'type',
        'status',
        'preferred_starts_at',
        'preferred_ends_at',
        'notes',
        'appointment_id',
        'waitlist_entry_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => BookingRequestType::class,
            'status' => BookingRequestStatus::class,
            'preferred_starts_at' => 'datetime',
            'preferred_ends_at' => 'datetime',
        ];
    }

    protected static function newFactory(): BookingRequestFactory
    {
        return BookingRequestFactory::new();
    }
}
