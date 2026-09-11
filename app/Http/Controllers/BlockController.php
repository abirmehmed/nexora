<?php

namespace App\Http\Controllers;

use App\Actions\BlockUser;
use App\Actions\UnblockUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class BlockController extends Controller
{
    public function store(Request $request, User $user, BlockUser $action): RedirectResponse
    {
        try {
            $action->execute($request->user(), $user);
            return back()->with('status', 'blocked');
        } catch (RuntimeException $e) {
            return back()->withErrors(['block' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, User $user, UnblockUser $action): RedirectResponse
    {
        $action->execute($request->user(), $user);
        return back()->with('status', 'unblocked');
    }
}
