<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();
        
        $existingLike = Like::where('user_id', $user->id)
            ->where('likeable_id', $post->id)
            ->where('likeable_type', Post::class)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Post unliked';
        } else {
            Like::create([
                'user_id' => $user->id,
                'likeable_id' => $post->id,
                'likeable_type' => Post::class,
            ]);
            $message = 'Post liked';
        }

        return back()->with('status', $message);
    }
}
