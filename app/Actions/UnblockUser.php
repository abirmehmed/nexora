<?php

namespace App\Actions;

use App\Models\User;

class UnblockUser
{
    public function execute(User $blocker, User $blocked): void
    {
        $blocker->blocks()->detach($blocked->id);
    }
}
