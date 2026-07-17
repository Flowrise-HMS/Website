<?php

namespace Modules\Website\Database\Seeders\Themes;

use Illuminate\Database\Seeder;
use Modules\Website\Database\Seeders\Concerns\SeedsWebsiteThemeContent;
use Modules\Website\Enums\MenuItemType;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;

class MedioxThemeSeeder extends Seeder
{
    use SeedsWebsiteThemeContent;

    public function run(): void
    {
        $this->activateTheme('mediox', [
            'meta_title' => config('app.name').' | Mediox',
            'meta_description' => 'Modern hospital care with online booking and patient-first services.',
            'footer_about_text' => 'Mediox-inspired hospital site demo for FlowRise HMS.',
            'brand_primary_color' => '#0B8A5A',
            'brand_secondary_color' => '#0F172A',
            'contact_email' => 'info@example.com',
            'contact_phone' => '+233 30 000 0000',
        ]);

        $this->seedPage([
            'slug' => 'home',
            'title' => 'Home',
            'type' => PageType::Home,
            'layout' => 'home-one',
            'sort_order' => 0,
            'meta_title' => 'Home | '.config('app.name'),
            'meta_description' => 'Quality care, experienced clinicians, and easy online booking.',
            'sections' => [
                [
                    'type' => SectionType::Hero,
                    'payload' => [
                        'heading' => 'Your health, our priority',
                        'subheading' => 'Trusted hospital care for every family',
                        'body' => 'Book outpatient visits, explore services, and stay updated with clinic news.',
                        'cta_label' => 'Make an Appointment',
                        'cta_url' => '/book-appointment',
                    ],
                ],
                [
                    'type' => SectionType::Stats,
                    'payload' => [
                        'heading' => 'Why patients choose us',
                        'items' => [
                            ['label' => 'Clinicians', 'value' => '40+'],
                            ['label' => 'Services', 'value' => '25+'],
                            ['label' => 'Years of care', 'value' => '15'],
                            ['label' => 'Patients served', 'value' => '20k+'],
                        ],
                    ],
                ],
                [
                    'type' => SectionType::ServicesGrid,
                    'payload' => [
                        'heading' => 'Medical Services',
                        'body' => 'Comprehensive outpatient, diagnostic, and specialty care.',
                        'items' => [
                            ['title' => 'General Medicine', 'body' => 'Primary consultations and chronic care.'],
                            ['title' => 'Diagnostics', 'body' => 'Laboratory and imaging support.'],
                            ['title' => 'Pharmacy', 'body' => 'On-site medication counselling and dispensing.'],
                        ],
                    ],
                ],
                [
                    'type' => SectionType::TeamGrid,
                    'payload' => [
                        'heading' => 'Meet our specialists',
                        'body' => 'Experienced clinicians dedicated to patient outcomes.',
                    ],
                ],
                [
                    'type' => SectionType::NewsTeaser,
                    'payload' => ['heading' => 'Health news & updates'],
                ],
                [
                    'type' => SectionType::Cta,
                    'payload' => [
                        'heading' => 'Ready to visit?',
                        'body' => 'Choose a convenient slot or leave a preferred time request.',
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
                    'heading' => 'About our hospital',
                    'body' => 'We combine clinical excellence with a welcoming patient experience across outpatient and specialty services.',
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
                    'heading' => 'Our services',
                    'body' => 'Browse care pathways available at this facility.',
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
                    'heading' => 'Talk to our care team',
                    'body' => 'Send a message and we will get back to you shortly.',
                ],
            ]],
        ]);

        $this->seedPrimaryMenu([
            ['label' => 'Home', 'type' => MenuItemType::Page, 'page_slug' => 'home'],
            ['label' => 'About', 'type' => MenuItemType::Page, 'page_slug' => 'about'],
            ['label' => 'Services', 'type' => MenuItemType::Page, 'page_slug' => 'services'],
            ['label' => 'News', 'type' => MenuItemType::Url, 'url' => '/news'],
            ['label' => 'Gallery', 'type' => MenuItemType::Url, 'url' => '/gallery'],
            ['label' => 'Team', 'type' => MenuItemType::Url, 'url' => '/team'],
            ['label' => 'Contact', 'type' => MenuItemType::Page, 'page_slug' => 'contact'],
            ['label' => 'Appointment', 'type' => MenuItemType::Url, 'url' => '/book-appointment'],
        ]);

        $this->seedTeamMembers([
            [
                'name' => 'Dr. Kwesi Boateng',
                'role' => 'Consultant Physician',
                'bio' => 'Internal medicine and outpatient leadership.',
            ],
            [
                'name' => 'Dr. Ama Serwaa',
                'role' => 'Paediatrician',
                'bio' => 'Child and adolescent clinical care.',
            ],
        ]);

        $this->seedPartners([
            ['name' => 'National Health Partners', 'url' => 'https://example.com'],
            ['name' => 'Regional Diagnostics Network', 'url' => 'https://example.com'],
        ]);

        $this->seedPosts([
            [
                'title' => 'New outpatient hours this month',
                'slug' => 'outpatient-hours-update',
                'excerpt' => 'Extended evening clinics for working families.',
                'body' => 'We have expanded weekday outpatient coverage. Book online or call the front desk.',
            ],
            [
                'title' => 'How to prepare for your visit',
                'slug' => 'prepare-for-visit',
                'excerpt' => 'Bring your ID, medication list, and insurance details.',
                'body' => 'Arriving prepared helps our clinicians deliver safer and faster care.',
            ],
        ]);
    }
}
