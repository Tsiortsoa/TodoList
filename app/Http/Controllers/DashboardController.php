<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Base query: tasks the user can see (assigned to them or created by them)
        // If admin, they see all tasks (or you could restrict)
        $query = Task::query();
        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('created_by', $user->id);
            });
        }

        // Statistics
        $stats = [
            'total_tasks'       => (clone $query)->count(),
            'pending_tasks'     => (clone $query)->where('status', 'pending')->count(),
            'completed_tasks'   => (clone $query)->where('status', 'completed')->count(),
            'overdue_tasks'     => (clone $query)->where('status', 'pending')
                ->where('due_date', '<', now())->count(),
            'my_tasks'          => Task::where('user_id', $user->id)->count(),
            'created_by_me'     => Task::where('created_by', $user->id)->count(),
        ];

        // Optional: add a "assigned by admin" stat for admins
        if ($user->isAdmin()) {
            $stats['assigned_by_admin'] = Task::where('created_by', '!=', $user->id)->count();
        }

        // Recent tasks (last 5)
        $recentTasks = (clone $query)->with(['assignedUser', 'creator'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentTasks'));
    }
}
