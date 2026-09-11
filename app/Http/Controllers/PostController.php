<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with(['user', 'likes'])->withCount('comments')->latest()->paginate(10);
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
        ]);

        $request->user()->posts()->create($validated);

        return redirect()->route('posts.index')->with('status', 'Post created successfully!');
    }

    public function show(Request $request, Post $post): View
    {
        $post->load(['user', 'comments.user', 'likes']);
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
        ]);

        $post->update($validated);

        return redirect()->route('posts.show', $post)->with('status', 'Post updated successfully!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post deleted successfully!');
    }
}
