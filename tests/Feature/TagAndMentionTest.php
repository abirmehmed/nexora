<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\UserMentioned;
use App\Services\TagParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TagAndMentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_hashtags_are_extracted_from_post_body(): void
    {
        $tags = TagParser::extractHashtags('This is a #Laravel post about #PHP and #WebDev!');
        
        $this->assertEquals(['laravel', 'php', 'webdev'], $tags);
    }

    public function test_mentions_are_extracted_from_post_body(): void
    {
        $mentions = TagParser::extractMentions('Hey @johndoe and @janedoe, check this out!');
        
        $this->assertEquals(['johndoe', 'janedoe'], $mentions);
    }

    public function test_post_creation_syncs_hashtags(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'My new post',
            'body' => 'Loving this new #laravel feature! #php',
        ]);

        $response->assertRedirect();

        $post = Post::latest()->first();
        $this->assertEquals(2, $post->tags->count());
        $this->assertTrue($post->tags->contains('name', 'laravel'));
        $this->assertTrue($post->tags->contains('name', 'php'));
    }

    public function test_mentioned_user_receives_notification(): void
    {
        Notification::fake();

        $author = User::factory()->create();
        $mentionedUser = User::factory()->create(['username' => 'testuser']);

        $this->actingAs($author)->post(route('posts.store'), [
            'title' => 'Hey there',
            'body' => 'Shoutout to @testuser for the help!',
        ]);

        Notification::assertSentTo(
            $mentionedUser,
            UserMentioned::class
        );
    }

    public function test_user_does_not_receive_notification_for_self_mention(): void
    {
        Notification::fake();

        $user = User::factory()->create(['username' => 'selfuser']);

        $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'My thoughts',
            'body' => 'I think @selfuser is doing great!',
        ]);

        Notification::assertNothingSent();
    }

    public function test_tag_page_displays_posts_with_that_tag(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'body' => 'This is a #testtag post',
        ]);
        
        // Manually sync to ensure it's there
        $tag = Tag::firstOrCreate(['name' => 'testtag']);
        $post->tags()->attach($tag->id);

        $response = $this->actingAs($user)->get(route('tags.show', 'testtag'));

        $response->assertOk();
        $response->assertSee('#testtag');
        $response->assertSee($post->title);
    }

    public function test_tag_parser_renders_clickable_hashtags(): void
    {
        $html = TagParser::renderBody('Check out #laravel');
        
        $this->assertStringContainsString('href="' . route('tags.show', 'laravel') . '"', $html);
        $this->assertStringContainsString('#laravel', $html);
    }

    public function test_tag_parser_renders_clickable_mentions(): void
    {
        $user = User::factory()->create(['username' => 'johndoe']);
        
        $html = TagParser::renderBody('Hello @johndoe');
        
        $this->assertStringContainsString('href="' . route('profile.show', 'johndoe') . '"', $html);
        $this->assertStringContainsString('@johndoe', $html);
    }
}
