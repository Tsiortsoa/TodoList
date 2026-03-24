<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class PromoteToAdminAction
{
    /**
     * Promote a user to admin role.
     */
    public function execute(User $user): User
    {
        return DB::transaction(function () use ($user) {
            $user->role = 'admin';
            $user->save();

            return $user;
        });
    }
}
