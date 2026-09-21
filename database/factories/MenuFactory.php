<?php

namespace Modules\Website\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Website\Models\Menu;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'location' => fake()->unique()->slug(2),
        ];
    }
}
