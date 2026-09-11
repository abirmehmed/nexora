<?php

namespace App\Actions;

use App\Models\User;
use RuntimeException;

class BlockUser
{
    public function execute(User $blocker, User $blocked): void
    {
        if ($blocker->id === $blocked->id) {
            throw new RuntimeException('You cannot block yourself.');
        }

        if (! $blocker->hasBlocked($blocked)) {
            $blocker->blocks()->attach($blocked->id);

            // Also unfollow each other
            $blocker->following()->detach($blocked->id);
            $blocked->following()->detach($blocker->id);
        }
    }
}
