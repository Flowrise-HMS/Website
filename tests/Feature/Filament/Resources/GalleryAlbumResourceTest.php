<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\GalleryAlbumResource;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages\CreateGalleryAlbum;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages\EditGalleryAlbum;
use Modules\Website\Filament\Clusters\Website\Resources\GalleryAlbums\Pages\ListGalleryAlbums;
use Modules\Website\Models\GalleryAlbum;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => GalleryAlbumResource::class,
    'subject' => 'GalleryAlbum',
    'model' => GalleryAlbum::class,
    'listPage' => ListGalleryAlbums::class,
    'createPage' => CreateGalleryAlbum::class,
    'editPage' => EditGalleryAlbum::class,
    'searchColumn' => 'title',
    'hasBulkDelete' => false,
    'hasRecordDelete' => true,
    'uniqueField' => 'slug',
    'createForm' => function (): array {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'is_published' => false,
            'sort_order' => 1,
            'items' => [],
        ];
    },
    'updateForm' => function (): array {
        $title = 'Updated '.fake()->unique()->sentence(2);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'items' => [],
        ];
    },
    'schemaState' => fn (mixed $test, GalleryAlbum $record): array => [
        'title' => $record->title,
        'slug' => $record->slug,
    ],
    'requiredValidation' => [
        'title is required' => [['title' => null], ['title' => 'required']],
        'slug is required' => [['slug' => null], ['slug' => 'required']],
    ],
    'databaseHasOnCreate' => fn (mixed $test, array $payload): array => [
        'title' => $payload['title'],
        'slug' => $payload['slug'],
    ],
]);
