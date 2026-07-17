<?php

namespace Modules\Website\Classes\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Modules\Appointment\Enums\AppointmentStatus;
use Modules\Appointment\Models\Appointment;
use Modules\Appointment\Models\ScheduleBlock;
use Modules\Core\Models\Service;
use Modules\Core\Support\ModuleAvailability;
use Modules\Website\Settings\WebsiteSettings;

class BookingSlotGenerator
{
    public function __construct(protected WebsiteSettings $settings) {}

    /**
     * @return list<array{starts_at: string, ends_at: string, label: string}>
     */
    public function forService(string $serviceId, ?Carbon $from = null): array
    {
        $branchId = $this->settings->booking_branch_id;
        if (! filled($branchId)) {
            return [];
        }

        if (! in_array($serviceId, $this->settings->bookable_service_ids, true)) {
            return [];
        }

        $service = Service::query()->find($serviceId);
        $slotMinutes = (int) ($service?->estimated_duration_minutes ?: $this->settings->booking_slot_minutes);
        $slotMinutes = max($slotMinutes, 5);

        $openWeekdays = collect($this->settings->booking_open_weekdays)->map(fn ($d) => (int) $d)->all();
        $horizon = max(1, (int) $this->settings->booking_horizon_days);
        $startDay = ($from?->copy() ?? now())->startOfDay();
        $endDay = $startDay->copy()->addDays($horizon);

        $open = Carbon::createFromFormat('H:i', $this->settings->booking_open_time) ?: Carbon::createFromTime(8);
        $close = Carbon::createFromFormat('H:i', $this->settings->booking_close_time) ?: Carbon::createFromTime(17);

        $busy = $this->busyWindows($branchId, $startDay, $endDay);

        $slots = [];
        foreach (CarbonPeriod::create($startDay, $endDay->copy()->subDay()) as $day) {
            /** @var Carbon $day */
            if (! in_array((int) $day->dayOfWeekIso, $openWeekdays, true)) {
                continue;
            }

            $cursor = $day->copy()->setTimeFrom($open);
            $dayClose = $day->copy()->setTimeFrom($close);

            while ($cursor->copy()->addMinutes($slotMinutes)->lte($dayClose)) {
                $slotEnd = $cursor->copy()->addMinutes($slotMinutes);

                if ($cursor->lte(now())) {
                    $cursor->addMinutes($slotMinutes);

                    continue;
                }

                if (! $this->overlapsBusy($cursor, $slotEnd, $busy)) {
                    $slots[] = [
                        'starts_at' => $cursor->toIso8601String(),
                        'ends_at' => $slotEnd->toIso8601String(),
                        'label' => $cursor->timezone(config('app.timezone'))->format('D, M j Y g:i A'),
                    ];
                }

                $cursor->addMinutes($slotMinutes);
            }
        }

        return $slots;
    }

    /**
     * @return Collection<int, array{start: Carbon, end: Carbon}>
     */
    protected function busyWindows(string $branchId, Carbon $from, Carbon $to): Collection
    {
        $windows = collect();

        if (! ModuleAvailability::appointmentEnabled()) {
            return $windows;
        }

        Appointment::query()
            ->where('branch_id', $branchId)
            ->whereNull('deleted_at')
            ->whereNotIn('status', [AppointmentStatus::CANCELLED, AppointmentStatus::NOSHOW])
            ->where('start_at', '<', $to)
            ->where('end_at', '>', $from)
            ->get(['start_at', 'end_at'])
            ->each(function (Appointment $appointment) use ($windows): void {
                $windows->push([
                    'start' => $appointment->start_at->copy(),
                    'end' => $appointment->end_at->copy(),
                ]);
            });

        ScheduleBlock::query()
            ->where('branch_id', $branchId)
            ->where('blocked_from', '<', $to)
            ->where('blocked_to', '>', $from)
            ->get(['blocked_from', 'blocked_to'])
            ->each(function (ScheduleBlock $block) use ($windows): void {
                $windows->push([
                    'start' => Carbon::parse($block->blocked_from),
                    'end' => Carbon::parse($block->blocked_to),
                ]);
            });

        return $windows;
    }

    /**
     * @param  Collection<int, array{start: Carbon, end: Carbon}>  $busy
     */
    protected function overlapsBusy(Carbon $start, Carbon $end, Collection $busy): bool
    {
        return $busy->contains(function (array $window) use ($start, $end): bool {
            return $window['start']->lt($end) && $window['end']->gt($start);
        });
    }
}
