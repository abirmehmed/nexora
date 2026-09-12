<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Notifications\PostLiked;
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
            
            // Notify post author (don't notify yourself)
            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostLiked($user, $post));
            }
            
            $message = 'Post liked';
        }

        return back()->with('status', $message);
    }
}
