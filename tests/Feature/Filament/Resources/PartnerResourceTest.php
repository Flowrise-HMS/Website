<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Pages\CreatePartner;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Pages\EditPartner;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\Pages\ListPartners;
use Modules\Website\Filament\Clusters\Website\Resources\Partner\PartnerResource;
use Modules\Website\Models\Partner;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => PartnerResource::class,
    'subject' => 'Partner',
    'model' => Partner::class,
    'listPage' => ListPartners::class,
    'createPage' => CreatePartner::class,
    'editPage' => EditPartner::class,
    'searchColumn' => 'name',
    'sortColumn' => 'name',
    'hasBulkDelete' => true,
    'hasRecordDelete' => true,
    'createForm' => fn (): array => [
        'name' => fake()->unique()->company(),
        'url' => 'https://partners.example.test',
        'is_published' => false,
        'sort_order' => 2,
    ],
    'updateForm' => fn (): array => [
        'name' => 'Updated '.fake()->unique()->company(),
        'url' => 'https://updated.example.test',
    ],
    'schemaState' => fn (mixed $test, Partner $record): array => [
        'name' => $record->name,
        'url' => $record->url,
    ],
    'requiredValidation' => [
        'name is required' => [['name' => null], ['name' => 'required']],
        'url must be a url' => [['url' => 'not-a-url'], ['url' => 'url']],
    ],
    'databaseHasOnCreate' => fn (mixed $test, array $payload): array => [
        'name' => $payload['name'],
        'url' => $payload['url'],
    ],
]);
