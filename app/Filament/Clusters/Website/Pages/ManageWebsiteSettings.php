<?php

namespace Modules\Website\Filament\Clusters\Website\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use Modules\Core\Enums\NavigationGroup;
use Modules\Core\Models\Branch;
use Modules\Core\Models\Service;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Filament\Clusters\Website\WebsiteCluster;
use Modules\Website\Settings\WebsiteSettings;

class ManageWebsiteSettings extends SettingsPage
{
    use HasPageShield;

    protected static ?string $cluster = WebsiteCluster::class;

    protected static string $settings = WebsiteSettings::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = NavigationGroup::ADMINISTRATION;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Site Settings';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Public site'))
                    ->description(__('When enabled, the hospital website is served at / and Filament moves to the configured path slug.'))
                    ->columns(2)
                    ->schema([
                        Toggle::make('website_enabled')
                            ->label(__('Enable public website'))
                            ->helperText(__('Disabling restores the admin panel to /.'))
                            ->live(),
                        TextInput::make('panel_path_slug')
                            ->label(__('Admin panel path'))
                            ->helperText(__('Single URL segment such as admin, app, or hms.'))
                            ->required()
                            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                            ->maxLength(40)
                            ->visible(fn ($get): bool => (bool) $get('website_enabled')),
                        Select::make('active_theme')
                            ->label(__('Active theme'))
                            ->options(fn (): array => app(ThemeManager::class)->themeOptions())
                            ->required(),
                        Toggle::make('animations_enabled')
                            ->label(__('Enable animations')),
                    ]),
                Section::make(__('Brand & appearance'))
                    ->description(__('Logo, colors, and footer copy used by the active theme.'))
                    ->columns(2)
                    ->schema([
                        FileUpload::make('brand_logo_path')
                            ->label(__('Logo (dark)'))
                            ->helperText(__('Shown on light backgrounds such as the header.'))
                            ->image()
                            ->disk('public')
                            ->directory('website/brand')
                            ->visibility('public')
                            ->maxSize(2048),
                        FileUpload::make('brand_logo_light_path')
                            ->label(__('Logo (light)'))
                            ->helperText(__('Shown on dark backgrounds such as the footer.'))
                            ->image()
                            ->disk('public')
                            ->directory('website/brand')
                            ->visibility('public')
                            ->maxSize(2048),
                        FileUpload::make('brand_favicon_path')
                            ->label(__('Favicon'))
                            ->image()
                            ->disk('public')
                            ->directory('website/brand')
                            ->visibility('public')
                            ->maxSize(512),
                        TextInput::make('brand_primary_color')
                            ->label(__('Primary color'))
                            ->placeholder('#1b84ff')
                            ->regex('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/')
                            ->helperText(__('Hex color used for buttons and accents.')),
                        TextInput::make('brand_secondary_color')
                            ->label(__('Secondary color'))
                            ->placeholder('#0f766e')
                            ->regex('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'),
                        Textarea::make('footer_about_text')
                            ->label(__('Footer about text'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make(__('Default SEO'))
                    ->columns(1)
                    ->schema([
                        TextInput::make('meta_title')->label(__('Default meta title'))->maxLength(255),
                        Textarea::make('meta_description')->label(__('Default meta description'))->rows(3),
                    ]),
                Section::make(__('Contact & booking CTA'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')->label(__('Contact email'))->email(),
                        TextInput::make('contact_phone')->label(__('Contact phone'))->tel(),
                        TextInput::make('booking_cta_phone')->label(__('Booking phone')),
                        TextInput::make('booking_cta_whatsapp')->label(__('Booking WhatsApp')),
                        Textarea::make('booking_cta_message')
                            ->label(__('Booking page message'))
                            ->columnSpanFull()
                            ->rows(3),
                    ]),
                Section::make(__('Online booking'))
                    ->description(__('Public Book Appointment uses curated Core services and these hours when the Appointment module is available.'))
                    ->columns(2)
                    ->schema([
                        Select::make('bookable_service_ids')
                            ->label(__('Bookable services'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn (): array => Service::query()
                                ->active()
                                ->nonMedication()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->helperText(__('Select from existing Core services (medication catalog entries are not bookable).'))
                            ->columnSpanFull(),
                        Select::make('booking_branch_id')
                            ->label(__('Default booking branch'))
                            ->searchable()
                            ->preload()
                            ->options(fn (): array => Branch::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all()),
                        CheckboxList::make('booking_open_weekdays')
                            ->label(__('Open weekdays'))
                            ->options([
                                1 => __('Monday'),
                                2 => __('Tuesday'),
                                3 => __('Wednesday'),
                                4 => __('Thursday'),
                                5 => __('Friday'),
                                6 => __('Saturday'),
                                7 => __('Sunday'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                        TextInput::make('booking_open_time')
                            ->label(__('Opens at'))
                            ->required()
                            ->placeholder('08:00'),
                        TextInput::make('booking_close_time')
                            ->label(__('Closes at'))
                            ->required()
                            ->placeholder('17:00'),
                        TextInput::make('booking_slot_minutes')
                            ->label(__('Slot length (minutes)'))
                            ->numeric()
                            ->minValue(5)
                            ->maxValue(240)
                            ->required(),
                        TextInput::make('booking_horizon_days')
                            ->label(__('Bookable days ahead'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(90)
                            ->required(),
                    ]),
            ]);
    }

    protected function afterSave(): void
    {
        try {
            Artisan::call('route:clear');
            Artisan::call('config:clear');
        } catch (\Throwable) {
            // Ignore when caches are not writable in some environments.
        }

        $settings = app(WebsiteSettings::class);
        $loginPath = $settings->website_enabled
            ? '/'.trim($settings->panel_path_slug, '/').'/login'
            : '/login';

        Notification::make()
            ->title(__('Website settings saved'))
            ->body(__('Admin login URL is now :url', ['url' => url($loginPath)]))
            ->success()
            ->persistent()
            ->send();
    }
}
