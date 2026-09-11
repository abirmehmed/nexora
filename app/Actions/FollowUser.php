<?php

namespace App\Actions;

use App\Models\User;
use RuntimeException;

class FollowUser
{
    public function execute(User $follower, User $following): void
    {
        if ($follower->id === $following->id) {
            throw new RuntimeException('You cannot follow yourself.');
        }

        if ($follower->isBlockedBy($following) || $following->isBlockedBy($follower)) {
            throw new RuntimeException('Cannot follow this user.');
        }

        if (! $follower->isFollowing($following)) {
            $follower->following()->attach($following->id);
        }
    }
}
