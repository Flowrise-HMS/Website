<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\MenuResource;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Pages\CreateMenu;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Pages\EditMenu;
use Modules\Website\Filament\Clusters\Website\Resources\Menus\Pages\ListMenus;
use Modules\Website\Models\Menu;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => MenuResource::class,
    'subject' => 'Menu',
    'model' => Menu::class,
    'listPage' => ListMenus::class,
    'createPage' => CreateMenu::class,
    'editPage' => EditMenu::class,
    'searchColumn' => 'name',
    'hasBulkDelete' => false,
    'hasRecordDelete' => true,
    'uniqueField' => 'location',
    'makeRecord' => fn (TestCase $test, array $attributes = []): Menu => Menu::factory()->create([
        'location' => 'primary',
        ...$attributes,
    ]),
    'makeRecords' => fn (TestCase $test, int $count) => collect(range(1, $count))->map(
        fn (int $offset): Menu => Menu::factory()->create([
            'name' => 'Nav Menu '.$offset,
            'location' => 'nav-'.$offset,
        ]),
    ),
    'createForm' => function (): array {
        Menu::query()->where('location', 'footer')->delete();

        return [
            'name' => fake()->unique()->words(2, true),
            'location' => 'footer',
            'items' => [],
        ];
    },
    'updateForm' => fn (mixed $test, Menu $record): array => [
        'name' => 'Updated '.$record->name,
        'location' => in_array($record->location, ['primary', 'footer'], true) ? $record->location : 'primary',
        'items' => [],
    ],
    'schemaState' => fn (mixed $test, Menu $record): array => [
        'name' => $record->name,
        'location' => $record->location,
    ],
    'requiredValidation' => [
        'name is required' => [['name' => null], ['name' => 'required']],
        'location is required' => [['location' => null], ['location' => 'required']],
    ],
    'databaseHasOnCreate' => fn (mixed $test, array $payload): array => [
        'name' => $payload['name'],
        'location' => $payload['location'],
    ],
]);
