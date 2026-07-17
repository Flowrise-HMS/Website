<?php

namespace Modules\Website\Providers;

use Modules\Core\Support\ModuleAvailability;
use Modules\Website\Classes\Support\AppointmentPublicBooking;
use Modules\Website\Classes\Support\NullBookingCtaResolver;
use Modules\Website\Classes\Support\NullPatientPublicLookup;
use Modules\Website\Classes\Support\NullPublicBooking;
use Modules\Website\Classes\Support\PatientModulePublicLookup;
use Modules\Website\Console\PublishThemeAssetsCommand;
use Modules\Website\Console\SeedThemeCommand;
use Modules\Website\Contracts\BookingCtaResolver;
use Modules\Website\Contracts\PatientPublicLookupContract;
use Modules\Website\Contracts\PublicBookingContract;
use Nwidart\Modules\Support\ModuleServiceProvider;

class WebsiteServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Website';

    protected string $nameLower = 'website';

    /**
     * @var list<class-string>
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * @var list<class-string>
     */
    protected array $commands = [
        PublishThemeAssetsCommand::class,
        SeedThemeCommand::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->bind(BookingCtaResolver::class, NullBookingCtaResolver::class);

        $this->app->bind(PatientPublicLookupContract::class, function ($app) {
            if (ModuleAvailability::patientEnabled()) {
                return $app->make(PatientModulePublicLookup::class);
            }

            return $app->make(NullPatientPublicLookup::class);
        });

        $this->app->bind(PublicBookingContract::class, function ($app) {
            if (ModuleAvailability::appointmentEnabled()) {
                return $app->make(AppointmentPublicBooking::class);
            }

            return $app->make(NullPublicBooking::class);
        });
    }

    public function boot(): void
    {
        parent::boot();

        $this->registerModulePermissions();
    }

    protected function registerModulePermissions(): void
    {
        $this->app->booted(function (): void {
            $permissions = config('website.permissions', []);
            if ($permissions === []) {
                return;
            }

            $existing = config('filament-shield.custom_permissions', []);
            config(['filament-shield.custom_permissions' => array_merge($existing, $permissions)]);
        });
    }
}
