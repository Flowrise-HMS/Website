<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('website', function ($blueprint): void {
            $blueprint->add('bookable_service_ids', []);
            $blueprint->add('booking_branch_id', null);
            $blueprint->add('booking_open_weekdays', [1, 2, 3, 4, 5]);
            $blueprint->add('booking_open_time', '08:00');
            $blueprint->add('booking_close_time', '17:00');
            $blueprint->add('booking_slot_minutes', 30);
            $blueprint->add('booking_horizon_days', 14);
        });
    }
};
