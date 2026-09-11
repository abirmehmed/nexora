<?php

namespace App\Http\Controllers;

use App\Actions\FollowUser;
use App\Actions\UnfollowUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class FollowController extends Controller
{
    public function store(Request $request, User $user, FollowUser $action): RedirectResponse
    {
        try {
            $action->execute($request->user(), $user);
            return back()->with('status', 'followed');
        } catch (RuntimeException $e) {
            return back()->withErrors(['follow' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, User $user, UnfollowUser $action): RedirectResponse
    {
        $action->execute($request->user(), $user);
        return back()->with('status', 'unfollowed');
    }
}
