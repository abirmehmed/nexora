<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\View\View;

class TagController extends Controller
{
    public function show(string $name): View
    {
        $tag = Tag::where('name', strtolower($name))->firstOrFail();

        $posts = $tag->posts()
            ->with(['user', 'likes'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('tags.show', compact('tag', 'posts'));
    }
}
