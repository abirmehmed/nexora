<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCommented;
use App\Notifications\PostLiked;
use App\Notifications\UserFollowed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_notification_when_post_is_liked(): void
    {
        $postAuthor = User::factory()->create();
        $liker = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postAuthor->id]);

        Notification::fake();

        $this->actingAs($liker)->post(route('posts.like', $post));

        Notification::assertSentTo(
            $postAuthor,
            PostLiked::class
        );
    }

    public function test_user_does_not_receive_notification_when_liking_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        Notification::fake();

        $this->actingAs($user)->post(route('posts.like', $post));

        Notification::assertNothingSent();
    }

    public function test_user_receives_notification_when_post_is_commented(): void
    {
        $postAuthor = User::factory()->create();
        $commenter = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postAuthor->id]);

        Notification::fake();

        $this->actingAs($commenter)->post(route('comments.store', $post), [
            'body' => 'Test comment'
        ]);

        Notification::assertSentTo(
            $postAuthor,
            PostCommented::class
        );
    }

    public function test_user_does_not_receive_notification_when_commenting_own_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        Notification::fake();

        $this->actingAs($user)->post(route('comments.store', $post), [
            'body' => 'Test comment'
        ]);

        Notification::assertNothingSent();
    }

    public function test_user_receives_notification_when_followed(): void
    {
        $follower = User::factory()->create();
        $followed = User::factory()->create();

        Notification::fake();

        $this->actingAs($follower)->post(route('users.follow', $followed));

        Notification::assertSentTo(
            $followed,
            UserFollowed::class
        );
    }

    public function test_notifications_index_page_displays_notifications(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Create a notification
        $otherUser->likes()->create([
            'likeable_id' => $post->id,
            'likeable_type' => Post::class,
        ]);
        $user->notify(new PostLiked($otherUser, $post));

        $response = $this->actingAs($user)->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('liked your post');
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Create notifications
        $user->notify(new PostLiked($otherUser, $post));
        $user->notify(new PostCommented($otherUser, $post));

        $this->assertEquals(2, $user->unreadNotifications->count());

        $response = $this->actingAs($user)->post(route('notifications.read'));

        $response->assertRedirect();
        $this->assertEquals(0, $user->fresh()->unreadNotifications->count());
    }

    public function test_unread_notification_count_displayed_in_navigation(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Create notifications
        $user->notify(new PostLiked($otherUser, $post));

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('1');
    }
}
