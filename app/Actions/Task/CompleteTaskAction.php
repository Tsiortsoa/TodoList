<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Models\Task;
use App\Services\RecurrenceService;
use Carbon\Carbon;

class CompleteTaskAction
{
    public function __construct(
        protected RecurrenceService $recurrenceService
    ) {}

    /**
     * Mark a task as completed and create the next recurring instance if needed.
     *
     * @param Task $task
     * @return Task The updated task (or new task if recurrence created)
     */
    public function execute(Task $task): Task
    {
        // Mark current task as completed
        $task->status = 'completed';
        $task->save();

        // If task is recurring, generate next instance
        if ($this->recurrenceService->shouldGenerateNext($task->recurrence)) {
            $this->createNextRecurringTask($task);
        }

        return $task->fresh();
    }

    /**
     * Create the next task instance based on recurrence.
     */
    protected function createNextRecurringTask(Task $originalTask): Task
    {
        $nextDueDate = $this->recurrenceService->calculateNextDueDate(
            $originalTask->recurrence,
            $originalTask->due_date
        );

        // If next due date could not be calculated, skip creation
        if (!$nextDueDate) {
            return $originalTask;
        }

        // Create new task with same attributes, but pending status
        $newTask = $originalTask->replicate(['deleted_at', 'created_at', 'updated_at']);
        $newTask->due_date = $nextDueDate;
        $newTask->status = 'pending';
        $newTask->save();

        // Replicate tags
        $newTask->tags()->sync($originalTask->tags->pluck('id'));

        return $newTask;
    }
}
