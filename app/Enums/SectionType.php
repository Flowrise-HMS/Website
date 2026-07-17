<?php

namespace Modules\Website\Enums;

enum SectionType: string
{
    case Hero = 'hero';
    case RichText = 'rich_text';
    case Stats = 'stats';
    case ServicesGrid = 'services_grid';
    case TeamGrid = 'team_grid';
    case PartnersLogo = 'partners_logo';
    case Gallery = 'gallery';
    case Faq = 'faq';
    case Cta = 'cta';
    case ContactForm = 'contact_form';
    case NewsTeaser = 'news_teaser';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
