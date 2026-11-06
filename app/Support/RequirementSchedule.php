<?php

namespace App\Support;

use App\Models\Requirement;
use Illuminate\Support\Carbon;

class RequirementSchedule
{
    /**
     * Due date of one submission.
     *  monthly: first Wednesday of the month AFTER the period (January DTR => first Wednesday of February)
     *  weekly:  Wednesday of the week AFTER the period
     *  none:    the requirement's own due date (may be null)
     */
    public static function dueDate(Requirement $requirement, string $period = ''): ?Carbon
    {
        return match ($requirement->recurrence) {
            'monthly' => Carbon::parse($period . '-01')->startOfDay()->addMonth()->firstOfMonth(Carbon::WEDNESDAY),
            'weekly'  => self::weekStart($period)->addDays(9),   // Monday + 7 = next Monday, + 2 = Wednesday
            default   => $requirement->due_on?->copy(),
        };
    }

    /**
     * Periods a student should already have submitted by $today (due date has arrived),
     * counting from the month/week they started their placement.
     *
     * @return string[]  e.g. ['2026-09', '2026-10'] or ['2026-W41', '2026-W42']
     */
    public static function periods(Requirement $requirement, ?Carbon $startedOn, ?Carbon $today = null): array
    {
        $today ??= today();

        if (! $startedOn || $requirement->recurrence === 'none') {
            return [];
        }

        $periods = [];

        if ($requirement->recurrence === 'monthly') {
            $cursor = $startedOn->copy()->startOfMonth();

            while (true) {
                $period = $cursor->format('Y-m');

                if (self::dueDate($requirement, $period)->gt($today)) {
                    break;
                }

                $periods[] = $period;
                $cursor->addMonth();
            }
        } else {
            $cursor = $startedOn->copy()->startOfWeek(Carbon::MONDAY);

            while (true) {
                $period = $cursor->format('o-\WW');   // ISO year + week, e.g. 2026-W44

                if (self::dueDate($requirement, $period)->gt($today)) {
                    break;
                }

                $periods[] = $period;
                $cursor->addWeek();
            }
        }

        return $periods;
    }

    /** Periods that are due but have no submission yet */
    public static function missing(Requirement $requirement, ?Carbon $startedOn, iterable $submittedPeriods): array
    {
        return array_values(array_diff(self::periods($requirement, $startedOn), collect($submittedPeriods)->all()));
    }

    /** "October 2026" or "Week of Oct 26" */
    public static function label(Requirement $requirement, string $period = ''): string
    {
        return match ($requirement->recurrence) {
            'monthly' => Carbon::parse($period . '-01')->format('F Y'),
            'weekly'  => 'Week of ' . self::weekStart($period)->format('M j'),
            default   => '',
        };
    }

    private static function weekStart(string $period): Carbon
    {
        // '2026-W44' => Monday of ISO week 44 of 2026
        return Carbon::now()->setISODate((int) substr($period, 0, 4), (int) substr($period, 6, 2))->startOfDay();
    }
}