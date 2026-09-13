<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Get IDs of people the user follows
        $followingIds = $user->following()->pluck('users.id');
        
        // Get posts from followed users + own posts
        $posts = Post::with(['user', 'likes'])
            ->withCount('comments')
            ->whereIn('user_id', $followingIds->merge([$user->id]))
            ->latest()
            ->paginate(10);

        // Get suggested users (people you don't follow, excluding yourself)
        $suggestedUsers = User::whereNotIn('id', $followingIds->merge([$user->id]))
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('feed', compact('posts', 'suggestedUsers'));
    }
}
