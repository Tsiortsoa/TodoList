<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Actions\User\ApproveUserAction;
use App\Actions\User\PromoteToAdminAction;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ApproveUserAction $approveUserAction,
        protected PromoteToAdminAction $promoteToAdminAction
    ) {}

    /**
     * List all users (admin only).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when($request->has('pending'), fn($q) => $q->whereNull('approved_at'))
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Approve a pending user.
     */
    public function approve(User $user)
    {
        $this->authorize('update', $user);

        $this->approveUserAction->execute($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User approved.');
    }

    /**
     * Promote a user to admin.
     */
    public function promote(User $user)
    {
        $this->authorize('update', $user);

        $this->promoteToAdminAction->execute($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User promoted to admin.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}