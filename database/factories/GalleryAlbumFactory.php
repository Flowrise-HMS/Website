<?php

namespace Modules\Website\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Website\Models\GalleryAlbum;

/**
 * @extends Factory<GalleryAlbum>
 */
class GalleryAlbumFactory extends Factory
{
    protected $model = GalleryAlbum::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->optional()->paragraph(),
            'cover_image' => null,
            'is_published' => false,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
