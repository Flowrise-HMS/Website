<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Website\Filament\Clusters\Website\Resources\Post\Pages\CreatePost;
use Modules\Website\Filament\Clusters\Website\Resources\Post\Pages\EditPost;
use Modules\Website\Filament\Clusters\Website\Resources\Post\Pages\ListPosts;
use Modules\Website\Filament\Clusters\Website\Resources\Post\PostResource;
use Modules\Website\Models\Post;
use Tests\Support\FilamentResourceTestSuite;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function (): void {
    $this->requireModule('Website');
    $this->migrateModules(['Core', 'Website']);
});

FilamentResourceTestSuite::register([
    'resource' => PostResource::class,
    'subject' => 'Post',
    'model' => Post::class,
    'listPage' => ListPosts::class,
    'createPage' => CreatePost::class,
    'editPage' => EditPost::class,
    'searchColumn' => 'title',
    'sortColumn' => 'title',
    'softDeletes' => true,
    'hasBulkDelete' => true,
    'hasRecordDelete' => true,
    'uniqueField' => 'slug',
    'createForm' => function (): array {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => 'Factory excerpt for a CMS post.',
        ];
    },
    'updateForm' => function (): array {
        $title = 'Updated '.fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
        ];
    },
    'schemaState' => fn (mixed $test, Post $record): array => [
        'title' => $record->title,
        'slug' => $record->slug,
    ],
    'requiredValidation' => [
        'title is required' => [['title' => null], ['title' => 'required']],
        'slug is required' => [['slug' => null], ['slug' => 'required']],
        'title is max 255' => [['title' => Str::random(256)], ['title' => 'max']],
    ],
    'databaseHasOnCreate' => fn (mixed $test, array $payload): array => [
        'title' => $payload['title'],
        'slug' => $payload['slug'],
    ],
]);
