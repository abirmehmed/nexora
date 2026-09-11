<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_comment_to_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store', $post), [
            'body' => 'This is a test comment',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'This is a test comment',
        ]);
    }

    public function test_comment_requires_body(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store', $post), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_user_can_delete_own_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_post_author_can_delete_comment(): void
    {
        $postAuthor = User::factory()->create();
        $commenter = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postAuthor->id]);
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $commenter->id,
        ]);

        $response = $this->actingAs($postAuthor)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_user_cannot_delete_others_comment(): void
    {
        $commenter = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $commenter->id,
        ]);

        $response = $this->actingAs($otherUser)->delete(route('comments.destroy', $comment));

        $response->assertForbidden();
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_post_show_displays_comments(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'Test comment body',
        ]);

        $response = $this->actingAs($user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('Test comment body');
        $response->assertSee($user->name);
    }
}
