<?php

namespace Modules\Website\Database\Seeders\Themes;

use Illuminate\Database\Seeder;
use Modules\Website\Database\Seeders\Concerns\SeedsWebsiteThemeContent;
use Modules\Website\Enums\MenuItemType;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;

class ClinicalMasterThemeSeeder extends Seeder
{
    use SeedsWebsiteThemeContent;

    public function run(): void
    {
        $this->activateTheme('clinicalmaster', [
            'meta_title' => config('app.name').' | Physiotherapy',
            'meta_description' => 'Physiotherapy and rehabilitation care with online appointment booking.',
            'footer_about_text' => 'Movement-focused care for recovery, wellness, and long-term mobility.',
            'brand_primary_color' => '#2062C5',
            'brand_secondary_color' => '#282C32',
            'contact_email' => 'rehab@example.com',
            'contact_phone' => '+233 30 111 2222',
        ]);

        $this->seedPage([
            'slug' => 'home',
            'title' => 'Home',
            'type' => PageType::Home,
            'layout' => 'index',
            'sort_order' => 0,
            'meta_title' => 'Physiotherapy Home | '.config('app.name'),
            'meta_description' => 'Restore strength and mobility with expert physiotherapy.',
            'sections' => [
                [
                    'type' => SectionType::Hero,
                    'payload' => [
                        'heading' => 'Move better. Recover stronger.',
                        'subheading' => 'Physiotherapy & rehabilitation',
                        'body' => 'Personalised rehab plans for sports injuries, post-surgery recovery, and chronic pain.',
                        'cta_label' => 'Book a Session',
                        'cta_url' => '/book-appointment',
                    ],
                ],
                [
                    'type' => SectionType::Stats,
                    'payload' => [
                        'heading' => 'Outcomes that matter',
                        'items' => [
                            ['label' => 'Therapists', 'value' => '12'],
                            ['label' => 'Treatment rooms', 'value' => '8'],
                            ['label' => 'Programs', 'value' => '18'],
                            ['label' => 'Patient rating', 'value' => '4.9'],
                        ],
                    ],
                ],
                [
                    'type' => SectionType::ServicesGrid,
                    'payload' => [
                        'heading' => 'Rehabilitation programs',
                        'body' => 'Evidence-based physiotherapy for every stage of recovery.',
                        'items' => [
                            ['title' => 'Sports Injury Rehab', 'body' => 'Return-to-play plans and performance recovery.'],
                            ['title' => 'Post-Surgical Therapy', 'body' => 'Guided mobility rebuild after orthopaedic procedures.'],
                            ['title' => 'Pain Management', 'body' => 'Hands-on therapy and progressive exercise.'],
                        ],
                    ],
                ],
                [
                    'type' => SectionType::TeamGrid,
                    'payload' => [
                        'heading' => 'Our therapists',
                        'body' => 'Licensed physiotherapists focused on functional recovery.',
                    ],
                ],
                [
                    'type' => SectionType::Faq,
                    'payload' => [
                        'heading' => 'Common questions',
                        'items' => [
                            ['question' => 'Do I need a referral?', 'answer' => 'Many patients self-refer. Bring previous imaging if available.'],
                            ['question' => 'How long is a session?', 'answer' => 'Standard sessions last 45–60 minutes depending on the program.'],
                        ],
                    ],
                ],
                [
                    'type' => SectionType::Cta,
                    'payload' => [
                        'heading' => 'Start your recovery plan',
                        'body' => 'Book an assessment with our physiotherapy team.',
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
            'layout' => 'about-us',
            'sort_order' => 1,
            'sections' => [[
                'type' => SectionType::RichText,
                'payload' => [
                    'heading' => 'About our clinic',
                    'body' => 'We help patients restore movement through physiotherapy, education, and measurable progress tracking.',
                ],
            ]],
        ]);

        $this->seedPage([
            'slug' => 'services',
            'title' => 'Services',
            'type' => PageType::Services,
            'layout' => 'services',
            'sort_order' => 2,
            'sections' => [[
                'type' => SectionType::ServicesGrid,
                'payload' => [
                    'heading' => 'Therapy services',
                    'body' => 'Choose a program that matches your recovery goals.',
                ],
            ]],
        ]);

        $this->seedPage([
            'slug' => 'team',
            'title' => 'Our Team',
            'type' => PageType::Teams,
            'layout' => 'team',
            'sort_order' => 3,
            'sections' => [[
                'type' => SectionType::TeamGrid,
                'payload' => [
                    'heading' => 'Meet the team',
                    'body' => 'Specialists across musculoskeletal, neuro, and sports rehab.',
                ],
            ]],
        ]);

        $this->seedPage([
            'slug' => 'faqs',
            'title' => 'FAQs',
            'type' => PageType::Custom,
            'layout' => 'faqs',
            'sort_order' => 4,
            'sections' => [[
                'type' => SectionType::Faq,
                'payload' => [
                    'heading' => 'FAQs',
                    'items' => [
                        ['question' => 'What should I wear?', 'answer' => 'Comfortable clothing that allows free movement.'],
                        ['question' => 'Can I reschedule?', 'answer' => 'Yes — contact the clinic at least 24 hours ahead when possible.'],
                    ],
                ],
            ]],
        ]);

        $this->seedPage([
            'slug' => 'contact',
            'title' => 'Contact Us',
            'type' => PageType::Contact,
            'layout' => 'contact-us',
            'sort_order' => 5,
            'sections' => [[
                'type' => SectionType::ContactForm,
                'payload' => [
                    'heading' => 'Contact the clinic',
                    'body' => 'Ask about programs, insurance, or your first assessment.',
                ],
            ]],
        ]);

        $this->seedPage([
            'slug' => 'book-appointment',
            'title' => 'Book Appointment',
            'type' => PageType::Booking,
            'layout' => 'appointment',
            'sort_order' => 6,
            'sections' => [[
                'type' => SectionType::Cta,
                'payload' => [
                    'heading' => 'Schedule your assessment',
                    'body' => 'Use the booking wizard below or call the clinic front desk.',
                    'cta_label' => 'Call Clinic',
                    'cta_url' => 'tel:+233301112222',
                ],
            ]],
        ]);

        $this->seedPrimaryMenu([
            ['label' => 'Home', 'type' => MenuItemType::Page, 'page_slug' => 'home'],
            ['label' => 'About', 'type' => MenuItemType::Page, 'page_slug' => 'about'],
            ['label' => 'Services', 'type' => MenuItemType::Page, 'page_slug' => 'services'],
            ['label' => 'Team', 'type' => MenuItemType::Page, 'page_slug' => 'team'],
            ['label' => 'FAQs', 'type' => MenuItemType::Page, 'page_slug' => 'faqs'],
            ['label' => 'News', 'type' => MenuItemType::Url, 'url' => '/news'],
            ['label' => 'Gallery', 'type' => MenuItemType::Url, 'url' => '/gallery'],
            ['label' => 'Contact', 'type' => MenuItemType::Page, 'page_slug' => 'contact'],
            // Booking is provided by the header CTA button — do not duplicate in the menu.
        ]);

        $this->seedTeamMembers([
            [
                'name' => 'Abena Owusu',
                'role' => 'Lead Physiotherapist',
                'bio' => 'Musculoskeletal and sports rehabilitation.',
            ],
            [
                'name' => 'Yaw Asante',
                'role' => 'Neuro Rehab Specialist',
                'bio' => 'Stroke and neurological recovery programs.',
            ],
        ]);

        $this->seedPartners([
            ['name' => 'Sports Recovery Hub', 'url' => 'https://example.com'],
            ['name' => 'Orthopaedic Partners', 'url' => 'https://example.com'],
        ]);

        $this->seedPosts([
            [
                'title' => '5 mobility drills for desk workers',
                'slug' => 'mobility-drills-desk-workers',
                'excerpt' => 'Simple daily movements to reduce stiffness.',
                'body' => 'These low-impact drills help maintain hip and shoulder mobility during long workdays.',
            ],
            [
                'title' => 'What to expect in your first physio visit',
                'slug' => 'first-physio-visit',
                'excerpt' => 'Assessment, goal setting, and a starter plan.',
                'body' => 'Your first session focuses on understanding pain patterns and defining measurable recovery goals.',
            ],
        ]);
    }
}
