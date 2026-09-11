<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_page_can_be_rendered(): void
    {
        $user = User::factory()->create(['username' => 'johndoe']);

        $response = $this->get(route('profile.show', ['username' => 'johndoe']));

        $response->assertStatus(200);
        $response->assertSee('johndoe');
    }

    public function test_public_profile_returns_404_for_nonexistent_user(): void
    {
        $response = $this->get(route('profile.show', ['username' => 'nonexistent']));

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'display_name' => 'Display Name',
            'bio' => 'This is my bio',
            'location' => 'New York',
            'website' => 'https://example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('Display Name', $user->display_name);
        $this->assertEquals('This is my bio', $user->bio);
        $this->assertEquals('New York', $user->location);
        $this->assertEquals('https://example.com', $user->website);
    }

    public function test_profile_update_validates_bio_length(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'bio' => str_repeat('a', 161),
        ]);

        $response->assertSessionHasErrors('bio');
    }

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertRedirect(route('profile.edit'));
        
        $user->refresh();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_user_can_upload_cover_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $response->assertRedirect(route('profile.edit'));
        
        $user->refresh();
        $this->assertNotNull($user->cover_image);
        Storage::disk('public')->assertExists($user->cover_image);
    }

    public function test_avatar_upload_validates_file_type(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->create('document.pdf', 100),
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_validates_file_size(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('avatar.jpg')->size(3000),
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_unauthenticated_user_cannot_update_profile(): void
    {
        $response = $this->patch(route('profile.update'), [
            'name' => 'Test',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }
}
