<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Website\Enums\PageType;
use Modules\Website\Filament\Clusters\Website\Resources\Pages\PageResource;
use Modules\Website\Filament\Clusters\Website\Resources\Pages\Pages\CreatePage;
use Modules\Website\Filament\Clusters\Website\Resources\Pages\Pages\EditPage;
use Modules\Website\Filament\Clusters\Website\Resources\Pages\Pages\ListPages;
use Modules\Website\Models\Page;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => PageResource::class,
    'subject' => 'Page',
    'model' => Page::class,
    'listPage' => ListPages::class,
    'createPage' => CreatePage::class,
    'editPage' => EditPage::class,
    'searchColumn' => 'title',
    'sortColumn' => 'title',
    'softDeletes' => true,
    'hasBulkDelete' => true,
    'hasRecordDelete' => true,
    'uniqueField' => 'slug',
    'createForm' => function (): array {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'type' => PageType::Custom->value,
            'is_published' => false,
            'sort_order' => 3,
        ];
    },
    'updateForm' => function (): array {
        $title = 'Updated '.fake()->unique()->sentence(2);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'type' => PageType::About->value,
        ];
    },
    'schemaState' => fn (mixed $test, Page $record): array => [
        'title' => $record->title,
        'slug' => $record->slug,
        'type' => $record->type,
    ],
    'requiredValidation' => [
        'title is required' => [['title' => null], ['title' => 'required']],
        'slug is required' => [['slug' => null], ['slug' => 'required']],
        'type is required' => [['type' => null], ['type' => 'required']],
    ],
    'databaseHasOnCreate' => fn (mixed $test, array $payload): array => [
        'title' => $payload['title'],
        'slug' => $payload['slug'],
        'type' => $payload['type'],
    ],
]);
