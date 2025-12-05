<?php

namespace Fundraiser\Identity\Adapters\Policies;

use Fundraiser\Identity\Adapters\Models\User;

class UserPolicy
{
    /** View list/search of users (admin only) */
    public function viewAny(User $user): bool
    {
        return $user->can('platform.access_admin');
    }

    /** View a specific user (kept conservative: admin only) */
    public function view(User $user, User $subject): bool
    {
        return $user->can('platform.access_admin');
    }
}
