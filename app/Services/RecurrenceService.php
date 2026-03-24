<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use InvalidArgumentException;

class RecurrenceService
{
    /**
     * Calculate the next due date based on recurrence rule and current due date.
     *
     * @param string $recurrence none, daily, weekly, monthly, yearly
     * @param Carbon $currentDueDate
     * @return Carbon|null Returns null if recurrence is 'none' or invalid.
     * @throws InvalidArgumentException
     */
    public function calculateNextDueDate(string $recurrence, Carbon $currentDueDate): ?Carbon
    {
        return match ($recurrence) {
            'none'    => null,
            'daily'   => $currentDueDate->copy()->addDay(),
            'weekly'  => $currentDueDate->copy()->addWeek(),
            'monthly' => $currentDueDate->copy()->addMonth(),
            'yearly'  => $currentDueDate->copy()->addYear(),
            default   => throw new InvalidArgumentException("Invalid recurrence rule: {$recurrence}"),
        };
    }

    /**
     * Determine if a task should generate a next instance based on its recurrence.
     */
    public function shouldGenerateNext(string $recurrence): bool
    {
        return $recurrence !== 'none';
    }
}
