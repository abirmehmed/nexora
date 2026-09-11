<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_posts(): void
    {
        $response = $this->get(route('posts.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_posts_index(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.index'));
        $response->assertOk();
        $response->assertSee($user->posts->first()->title);
    }

    public function test_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Test Post',
            'body' => 'This is the body of the test post.',
        ]);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Test Post',
            'body' => 'This is the body of the test post.',
        ]);
    }

    public function test_post_requires_title_and_body(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);
    }

    public function test_user_can_view_single_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('posts.show', $post));
        $response->assertOk();
        $response->assertSee($post->title);
        $response->assertSee($post->body);
    }

    public function test_user_can_update_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'body' => 'Updated body content.',
        ]);

        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'body' => 'Updated body content.',
        ]);
    }

    public function test_user_cannot_update_others_post(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Hacked Title',
            'body' => 'Hacked body.',
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_others_post(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
