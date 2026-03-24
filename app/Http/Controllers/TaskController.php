<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Actions\Task\CompleteTaskAction;
use App\Actions\Task\DispatchTaskAction;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected CompleteTaskAction $completeTaskAction,
        protected DispatchTaskAction $dispatchTaskAction
    ) {}

    /**
     * Display a listing of tasks with filtering.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Base query
        $query = Task::with(['assignedUser', 'creator', 'tags']);

        // Apply role-based visibility
        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }

        // Filter by status (including trash)
        if ($request->has('status') && $request->status === 'trash') {
            $query->onlyTrashed();
        } elseif ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: active (pending) only
            $query->pending();
        }

        // Date filters
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'overdue':
                    $query->where('due_date', '<', now())->pending();
                    break;
                case 'today':
                    $query->whereDate('due_date', today());
                    break;
                case 'upcoming':
                    $query->where('due_date', '>', now())->pending();
                    break;
            }
        }

        // Tag filter
        if ($request->has('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $request->tag));
        }

        // Assignment filter
        if ($request->has('assigned')) {
            if ($request->assigned === 'my') {
                $query->where('user_id', $user->id);
            } elseif ($request->assigned === 'by_admin') {
                $query->where('created_by', '!=', $user->id);
            }
        }

        $tasks = $query->latest()->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show form to create a new task.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Task::class);

        $users = $request->user()->isAdmin()
            ? User::approved()->get()
            : collect([$request->user()]);

        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $user = $request->user();

        // Non‑admins can only assign to themselves
        if (!$user->isAdmin()) {
            $request->merge(['user_id' => $user->id]);
        }

        $task = $this->dispatchTaskAction->execute($request, $user);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show form to edit the task.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $users = $task->creator->isAdmin()
            ? User::approved()->get()
            : collect([$task->user]);

        return view('tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the task.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        if ($request->has('tags')) {
            $task->tags()->sync($request->tags);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated.');
    }

    /**
     * Mark task as completed (with recurrence handling).
     */
    public function complete(Task $task)
    {
        $this->authorize('update', $task);

        $this->completeTaskAction->execute($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Task completed.');
    }

    /**
     * Soft delete the task (move to trash).
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task moved to trash.');
    }

    /**
     * Restore a soft‑deleted task.
     */
    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $this->authorize('restore', $task);
        $task->restore();

        return redirect()->route('tasks.index', ['status' => 'trash'])
            ->with('success', 'Task restored.');
    }

    /**
     * Permanently delete a task.
     */
    public function forceDelete($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $task);
        $task->forceDelete();

        return redirect()->route('tasks.index', ['status' => 'trash'])
            ->with('success', 'Task permanently deleted.');
    }
}