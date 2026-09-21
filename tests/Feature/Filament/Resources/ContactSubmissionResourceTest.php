<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\ContactSubmissionResource;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Pages\EditContactSubmission;
use Modules\Website\Filament\Clusters\Website\Resources\ContactSubmission\Pages\ListContactSubmissions;
use Modules\Website\Models\ContactSubmission;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => ContactSubmissionResource::class,
    'subject' => 'ContactSubmission',
    'model' => ContactSubmission::class,
    'listPage' => ListContactSubmissions::class,
    'editPage' => EditContactSubmission::class,
    'searchColumn' => 'name',
    'sortColumn' => 'created_at',
    'hasBulkDelete' => true,
    'hasRecordDelete' => true,
    'makeRecords' => function (TestCase $test, int $count) {
        return collect(range(1, $count))->map(fn (int $offset): ContactSubmission => ContactSubmission::factory()->create([
            'name' => 'Contact Visitor '.$offset,
            'created_at' => now()->subDays($offset),
        ]));
    },
    'updateForm' => fn (): array => [
        'read_at' => now()->toDateTimeString(),
    ],
    'schemaState' => fn (mixed $test, ContactSubmission $record): array => [
        'name' => $record->name,
        'email' => $record->email,
        'message' => $record->message,
    ],
    'databaseHasOnCreate' => fn (mixed $test, array $payload): array => $payload,
]);
