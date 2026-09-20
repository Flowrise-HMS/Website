<?php

namespace Modules\Website\Database\Seeders;

use Modules\Website\Database\Seeders\Themes\ClinicalBlueThemeSeeder;
use Modules\Website\Database\Seeders\Themes\ClinicalMasterThemeSeeder;
use Modules\Website\Database\Seeders\Themes\DefaultThemeSeeder;
use Modules\Website\Database\Seeders\Themes\MedioxThemeSeeder;

class ThemeSeederRegistry
{
    /**
     * @return array<string, class-string>
     */
    public static function all(): array
    {
        return [
            'default' => DefaultThemeSeeder::class,
            'mediox' => MedioxThemeSeeder::class,
            'clinicalmaster' => ClinicalMasterThemeSeeder::class,
            'clinical-blue' => ClinicalBlueThemeSeeder::class,
        ];
    }

    /**
     * @return class-string
     */
    public static function resolve(string $theme): string
    {
        $theme = strtolower(trim($theme));
        $map = self::all();

        if (! isset($map[$theme])) {
            throw new \InvalidArgumentException(
                'Unknown theme seeder ['.$theme.']. Available: '.implode(', ', array_keys($map))
            );
        }

        return $map[$theme];
    }

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::all());
    }
}
