<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Pages\CreateTeamMember;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Pages\EditTeamMember;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\Pages\ListTeamMembers;
use Modules\Website\Filament\Clusters\Website\Resources\TeamMember\TeamMemberResource;
use Modules\Website\Models\TeamMember;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => TeamMemberResource::class,
    'subject' => 'TeamMember',
    'model' => TeamMember::class,
    'listPage' => ListTeamMembers::class,
    'createPage' => CreateTeamMember::class,
    'editPage' => EditTeamMember::class,
    'searchColumn' => 'name',
    'sortColumn' => 'name',
    'hasBulkDelete' => true,
    'hasRecordDelete' => true,
    'createForm' => fn (): array => [
        'name' => fake()->unique()->name(),
        'role' => 'Consultant',
        'is_published' => false,
        'sort_order' => 1,
    ],
    'updateForm' => fn (): array => [
        'name' => 'Updated '.fake()->unique()->name(),
        'role' => 'Medical Director',
    ],
    'schemaState' => fn (mixed $test, TeamMember $record): array => [
        'name' => $record->name,
        'role' => $record->role,
    ],
    'requiredValidation' => [
        'name is required' => [['name' => null], ['name' => 'required']],
        'name is max 255' => [['name' => Str::random(256)], ['name' => 'max']],
    ],
    'databaseHasOnCreate' => fn (mixed $test, array $payload): array => [
        'name' => $payload['name'],
        'role' => $payload['role'],
    ],
]);
