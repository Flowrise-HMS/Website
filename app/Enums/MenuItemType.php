<?php

namespace Modules\Website\Enums;

enum MenuItemType: string
{
    case Page = 'page';
    case Url = 'url';
    case Cta = 'cta';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
