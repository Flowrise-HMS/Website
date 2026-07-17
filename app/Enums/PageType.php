<?php

namespace Modules\Website\Enums;

enum PageType: string
{
    case Home = 'home';
    case About = 'about';
    case Services = 'services';
    case Contact = 'contact';
    case Partners = 'partners';
    case Teams = 'teams';
    case Custom = 'custom';
    case Booking = 'booking';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
