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

        // Get IDs of users this user follows + their own ID
        $followingIds = $user->following()->pluck('users.id')->toArray();
        $followingIds[] = $user->id;

        // Get posts from followed users and self, ordered by newest
        $posts = Post::with('user')
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->paginate(10);

        // Suggest users to follow (not following, not self)
        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $followingIds)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('feed.index', compact('posts', 'suggestedUsers'));
    }
}
