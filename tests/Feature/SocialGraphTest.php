<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialGraphTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_follow_another_user(): void
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();

        $response = $this->actingAs($follower)
            ->post(route('users.follow', $following));

        $response->assertRedirect();
        $this->assertTrue($follower->fresh()->isFollowing($following));
    }

    public function test_user_can_unfollow(): void
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();
        $follower->following()->attach($following->id);

        $response = $this->actingAs($follower)
            ->delete(route('users.unfollow', $following));

        $response->assertRedirect();
        $this->assertFalse($follower->fresh()->isFollowing($following));
    }

    public function test_user_cannot_follow_themselves(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('users.follow', $user));

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->isFollowing($user));
    }

    public function test_user_cannot_follow_when_blocked(): void
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();
        $following->blocks()->attach($follower->id);

        $response = $this->actingAs($follower)
            ->post(route('users.follow', $following));

        $response->assertRedirect();
        $this->assertFalse($follower->fresh()->isFollowing($following));
    }

    public function test_user_can_block_another_user(): void
    {
        $blocker = User::factory()->create();
        $blocked = User::factory()->create();

        $response = $this->actingAs($blocker)
            ->post(route('users.block', $blocked));

        $response->assertRedirect();
        $this->assertTrue($blocker->fresh()->hasBlocked($blocked));
    }

    public function test_user_can_unblock(): void
    {
        $blocker = User::factory()->create();
        $blocked = User::factory()->create();
        $blocker->blocks()->attach($blocked->id);

        $response = $this->actingAs($blocker)
            ->delete(route('users.unblock', $blocked));

        $response->assertRedirect();
        $this->assertFalse($blocker->fresh()->hasBlocked($blocked));
    }

    public function test_user_cannot_block_themselves(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('users.block', $user));

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->hasBlocked($user));
    }

    public function test_blocking_removes_follow_relationship(): void
    {
        $blocker = User::factory()->create();
        $blocked = User::factory()->create();
        $blocker->following()->attach($blocked->id);
        $blocked->following()->attach($blocker->id);

        $this->actingAs($blocker)
            ->post(route('users.block', $blocked));

        $blocker = $blocker->fresh();
        $blocked = $blocked->fresh();

        $this->assertFalse($blocker->isFollowing($blocked));
        $this->assertFalse($blocked->isFollowing($blocker));
        $this->assertTrue($blocker->hasBlocked($blocked));
    }

    public function test_unauthenticated_user_cannot_follow(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('users.follow', $user));

        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_user_cannot_block(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('users.block', $user));

        $response->assertRedirect(route('login'));
    }
}
