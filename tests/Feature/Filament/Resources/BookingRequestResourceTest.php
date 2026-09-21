<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Enums\BookingRequestStatus;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\BookingRequestResource;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Pages\EditBookingRequest;
use Modules\Website\Filament\Clusters\Website\Resources\BookingRequest\Pages\ListBookingRequests;
use Modules\Website\Models\BookingRequest;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => BookingRequestResource::class,
    'subject' => 'BookingRequest',
    'model' => BookingRequest::class,
    'listPage' => ListBookingRequests::class,
    'editPage' => EditBookingRequest::class,
    'sortColumn' => 'preferred_starts_at',
    'hasBulkDelete' => true,
    'hasRecordDelete' => true,
    'makeRecords' => function (TestCase $test, int $count) {
        return collect(range(1, $count))->map(fn (int $offset): BookingRequest => BookingRequest::factory()->create([
            'preferred_starts_at' => now()->addDays($offset)->setTime(9, 0),
            'preferred_ends_at' => now()->addDays($offset)->setTime(9, 30),
        ]));
    },
    'updateForm' => fn (): array => [
        'status' => BookingRequestStatus::Accepted->value,
    ],
    'schemaState' => fn (mixed $test, BookingRequest $record): array => [
        'status' => $record->status instanceof BookingRequestStatus ? $record->status->value : $record->status,
        'type' => $record->type instanceof BackedEnum ? $record->type->value : $record->type,
    ],
]);
