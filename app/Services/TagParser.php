<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\User;

class TagParser
{
    public static function extractHashtags(string $text): array
    {
        preg_match_all('/#(\w+)/', $text, $matches);
        return array_unique(array_map('strtolower', $matches[1]));
    }

    public static function extractMentions(string $text): array
    {
        preg_match_all('/@(\w+)/', $text, $matches);
        return array_unique($matches[1]);
    }

    public static function syncTagsForPost(object $post, string $body): void
    {
        $tagNames = self::extractHashtags($body);
        
        $tagIds = [];
        foreach ($tagNames as $name) {
            $tag = Tag::firstOrCreate(['name' => $name]);
            $tagIds[] = $tag->id;
        }
        
        $post->tags()->sync($tagIds);
    }

    public static function renderBody(string $body): string
    {
        // Escape the body first to prevent XSS
        $escapedBody = e($body);

        // Replace #hashtags with links
        $escapedBody = preg_replace_callback(
            '/#(\w+)/',
            fn($m) => '<a href="' . route('tags.show', strtolower($m[1])) . '" class="text-primary-600 hover:text-primary-700 font-medium">#' . e($m[1]) . '</a>',
            $escapedBody
        );

        // Replace @mentions with links
        $escapedBody = preg_replace_callback(
            '/@(\w+)/',
            function ($m) {
                $user = User::where('username', $m[1])->first();
                if ($user) {
                    return '<a href="' . route('profile.show', $user->username) . '" class="text-primary-600 hover:text-primary-700 font-medium">@' . e($m[1]) . '</a>';
                }
                return '@' . e($m[1]);
            },
            $escapedBody
        );

        return nl2br($escapedBody);
    }
}
