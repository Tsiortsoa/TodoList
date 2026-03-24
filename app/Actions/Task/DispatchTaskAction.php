<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Models\Task;
use App\Models\User;
use App\Http\Requests\StoreTaskRequest;

class DispatchTaskAction
{
    /**
     * Create a task assigned to a specific user, with creator set to current admin.
     *
     * @param StoreTaskRequest $request
     * @param User $creator
     * @return Task
     */
    public function execute(StoreTaskRequest $request, User $creator): Task
    {
        $task = new Task($request->validated());
        $task->created_by = $creator->id;
        $task->status = $task->status ?? 'pending'; // default pending if not provided
        $task->save();

        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        }

        return $task;
    }
}