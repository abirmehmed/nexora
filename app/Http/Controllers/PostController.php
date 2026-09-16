<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Notifications\UserMentioned;
use App\Services\TagParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with(['user', 'likes', 'tags'])->withCount('comments')->latest()->paginate(10);
        $user = $request->user();

        return view('posts.index', compact('posts', 'user'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $data = $request->only(['title', 'body']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = $request->user()->posts()->create($data);

        // Sync hashtags
        TagParser::syncTagsForPost($post, $validated['body']);

        // Notify @mentions
        $mentionedUsernames = TagParser::extractMentions($validated['body']);
        foreach ($mentionedUsernames as $username) {
            $mentionedUser = \App\Models\User::where('username', $username)->first();
            if ($mentionedUser && $mentionedUser->id !== $request->user()->id) {
                $mentionedUser->notify(new UserMentioned($request->user(), $post));
            }
        }

        return redirect()->route('posts.index')->with('status', 'Post created successfully!');
    }

    public function show(Request $request, Post $post): View
    {
        $post->load(['user', 'comments.user', 'likes', 'tags']);
        $user = $request->user();

        return view('posts.show', compact('post', 'user'));
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $data = $request->only(['title', 'body']);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        // Re-sync tags on update
        TagParser::syncTagsForPost($post, $validated['body']);

        return redirect()->route('posts.show', $post)->with('status', 'Post updated successfully!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        // Delete associated image
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post deleted successfully!');
    }
}
