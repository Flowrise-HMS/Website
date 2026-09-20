<?php

namespace Modules\Website\Database\Seeders\Themes;

use Illuminate\Database\Seeder;
use Modules\Website\Database\Seeders\Concerns\SeedsWebsiteThemeContent;
use Modules\Website\Enums\MenuItemType;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;

class ClinicalBlueThemeSeeder extends Seeder
{
    use SeedsWebsiteThemeContent;

    public function run(): void
    {
        $this->activateTheme('clinical-blue', [
            'meta_title' => config('app.name'),
            'meta_description' => 'Compassionate hospital care for your community.',
            'footer_about_text' => 'Trusted clinical care for patients and families.',
        ]);

        $this->seedPage([
            'slug' => 'home',
            'title' => 'Home',
            'type' => PageType::Home,
            'sort_order' => 0,
            'meta_title' => 'Welcome to '.config('app.name'),
            'meta_description' => 'Compassionate hospital care for your community.',
            'sections' => [
                [
                    'type' => SectionType::Hero,
                    'payload' => [
                        'heading' => 'Clinical excellence, close to home',
                        'subheading' => 'Specialist clinics, diagnostics and emergency care under one roof.',
                        'cta_label' => 'Book Appointment',
                        'cta_url' => '/book-appointment',
                    ],
                ],
                [
                    'type' => SectionType::ServicesGrid,
                    'payload' => [
                        'heading' => 'Our Services',
                        'body' => 'Outpatient care, diagnostics, pharmacy, and more.',
                    ],
                ],
                [
                    'type' => SectionType::NewsTeaser,
                    'payload' => ['heading' => 'Latest news'],
                ],
                [
                    'type' => SectionType::Cta,
                    'payload' => [
                        'heading' => 'Need to see a clinician?',
                        'body' => 'Call us or send a WhatsApp message to schedule your visit.',
                        'cta_label' => 'Book Appointment',
                        'cta_url' => '/book-appointment',
                    ],
                ],
            ],
        ]);

        $this->seedPage([
            'slug' => 'about',
            'title' => 'About Us',
            'type' => PageType::About,
            'sort_order' => 1,
            'sections' => [[
                'type' => SectionType::RichText,
                'payload' => [
                    'heading' => 'About Us',
                    'body' => 'Content managed from the Website admin cluster.',
                ],
            ]],
        ]);

        $this->seedPage([
            'slug' => 'services',
            'title' => 'Services',
            'type' => PageType::Services,
            'sort_order' => 2,
            'sections' => [[
                'type' => SectionType::ServicesGrid,
                'payload' => [
                    'heading' => 'Services',
                    'body' => 'Explore the care pathways available at our facility.',
                ],
            ]],
        ]);

        $this->seedPage([
            'slug' => 'contact',
            'title' => 'Contact Us',
            'type' => PageType::Contact,
            'sort_order' => 3,
            'sections' => [[
                'type' => SectionType::ContactForm,
                'payload' => [
                    'heading' => 'Contact Us',
                    'body' => 'Send us a message and our team will respond shortly.',
                ],
            ]],
        ]);

        $this->seedPrimaryMenu([
            ['label' => 'Home', 'type' => MenuItemType::Page, 'page_slug' => 'home'],
            ['label' => 'About Us', 'type' => MenuItemType::Page, 'page_slug' => 'about'],
            ['label' => 'Services', 'type' => MenuItemType::Page, 'page_slug' => 'services'],
            ['label' => 'News', 'type' => MenuItemType::Url, 'url' => '/news'],
            ['label' => 'Gallery', 'type' => MenuItemType::Url, 'url' => '/gallery'],
            ['label' => 'Team', 'type' => MenuItemType::Url, 'url' => '/team'],
            ['label' => 'Partners', 'type' => MenuItemType::Url, 'url' => '/partners'],
            ['label' => 'Contact Us', 'type' => MenuItemType::Page, 'page_slug' => 'contact'],
            ['label' => 'Book Appointment', 'type' => MenuItemType::Url, 'url' => '/book-appointment'],
        ]);

        $this->seedTeamMembers([
            [
                'name' => 'Dr. Ada Mensah',
                'role' => 'Medical Director',
                'bio' => 'Leading clinical quality and patient experience.',
            ],
        ]);

        $this->seedPartners([
            ['name' => 'Community Health Alliance', 'url' => 'https://example.com'],
        ]);

        $this->seedPosts([
            [
                'title' => 'Welcome to our new hospital website',
                'slug' => 'welcome-website',
                'excerpt' => 'Explore services, news, and ways to reach our care team.',
                'body' => 'We are excited to share health tips, service updates, and community news here.',
            ],
        ]);
    }
}
