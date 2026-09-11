<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_feed(): void
    {
        $response = $this->get(route('feed'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_feed(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('feed'));
        $response->assertOk();
        $response->assertSee($post->title);
    }

    public function test_feed_shows_posts_from_followed_users(): void
    {
        $user = User::factory()->create();
        $followedUser = User::factory()->create();
        $unfollowedUser = User::factory()->create();

        $user->following()->attach($followedUser->id);

        $followedPost = Post::factory()->create(['user_id' => $followedUser->id, 'title' => 'Followed Post']);
        $unfollowedPost = Post::factory()->create(['user_id' => $unfollowedUser->id, 'title' => 'Unfollowed Post']);

        $response = $this->actingAs($user)->get(route('feed'));
        $response->assertOk();
        $response->assertSee('Followed Post');
        $response->assertDontSee('Unfollowed Post');
    }

    public function test_feed_shows_suggested_users(): void
    {
        $user = User::factory()->create();
        $suggestedUser = User::factory()->create();

        $response = $this->actingAs($user)->get(route('feed'));
        $response->assertOk();
        $response->assertSee($suggestedUser->username);
    }
}
