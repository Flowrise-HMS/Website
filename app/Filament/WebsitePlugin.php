<?php

namespace Modules\Website\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class WebsitePlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Website';
    }

    public function getId(): string
    {
        return 'website';
    }

    public function boot(Panel $panel): void {}
}
