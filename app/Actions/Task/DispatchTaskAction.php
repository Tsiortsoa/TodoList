<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Models\Task;
use App\Models\User;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskAssignedNotification;

class DispatchTaskAction
{
    /**
     * Create a task assigned to a specific user, with creator set to current admin.
     */
    public function execute(StoreTaskRequest $request, User $creator): Task
    {
        $task = new Task($request->validated());
        $task->created_by = $creator->id;
        $task->status = $task->status ?? 'pending';
        $task->save();

        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        }

        // Send notification if the creator is an admin and the assigned user is not the creator
        if ($creator->isAdmin() && $task->user_id !== $creator->id) {
            $assignedUser = User::find($task->user_id);
            if ($assignedUser) {
                Notification::send($assignedUser, new TaskAssignedNotification($task));
            }
        }

        return $task;
    }
}