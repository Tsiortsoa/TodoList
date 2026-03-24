<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserApproved; // You would create this notification later

class ApproveUserAction
{
    /**
     * Approve a pending user.
     *
     * @param User $user
     * @param bool $notify Whether to send a notification (default true)
     * @return User
     */
    public function execute(User $user, bool $notify = true): User
    {
        return DB::transaction(function () use ($user, $notify) {
            $user->approved_at = Carbon::now();
            $user->save();

            if ($notify) {
                // Send welcome/approved notification
                // Notification::send($user, new UserApproved());
                // For now, we'll just comment it out until notification is created
            }

            return $user;
        });
    }
}
