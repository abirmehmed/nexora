<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_like_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->post(route('posts.like', $post));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_like_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.like', $post));

        $response->assertRedirect();
        $this->assertTrue($user->hasLiked($post));
        $this->assertEquals(1, $post->likes()->count());
    }

    public function test_user_can_unlike_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Like the post first
        Like::create([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class,
        ]);
        $this->assertTrue($user->hasLiked($post));

        // Unlike the post
        $response = $this->actingAs($user)->post(route('posts.like', $post));

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->hasLiked($post));
        $this->assertEquals(0, $post->likes()->count());
    }

    public function test_like_count_displayed_on_post_index(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Add some likes
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post->likes()->create(['user_id' => $user1->id]);
        $post->likes()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user)->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee('2');
    }

    public function test_like_count_displayed_on_post_show(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Add some likes
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post->likes()->create(['user_id' => $user1->id]);
        $post->likes()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('2');
    }

    public function test_liked_state_shown_when_user_has_liked(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // User likes the post
        Like::create([
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => Post::class,
        ]);

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('Liked');
    }

    public function test_like_state_not_shown_when_user_has_not_liked(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('Like');
        $response->assertDontSee('Liked');
    }
}
