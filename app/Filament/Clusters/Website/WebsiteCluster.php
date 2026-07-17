<?php

namespace Modules\Website\Filament\Clusters\Website;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Modules\Core\Enums\NavigationGroup;

class WebsiteCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static ?string $navigationLabel = 'Website';

    protected static ?int $navigationSort = 40;

    protected static bool $shouldRegisterSubNavigation = true;
}
