<?php

namespace App\Actions;

use App\Models\User;

class UnfollowUser
{
    public function execute(User $follower, User $following): void
    {
        $follower->following()->detach($following->id);
    }
}
