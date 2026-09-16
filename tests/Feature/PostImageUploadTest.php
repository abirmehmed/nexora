<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_image_with_post(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'My Photo Post',
            'body' => 'Check out this photo! #photography',
            'image' => $file,
        ]);

        $response->assertRedirect();

        $post = Post::latest()->first();
        $this->assertNotNull($post->image);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_user_can_update_post_with_new_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $oldFile = UploadedFile::fake()->image('old.jpg');
        $newFile = UploadedFile::fake()->image('new.jpg');

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'image' => $oldFile->store('posts', 'public'),
        ]);

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'body' => 'Updated body',
            'image' => $newFile,
        ]);

        $response->assertRedirect();

        Storage::disk('public')->assertMissing($post->getOriginal('image'));
        Storage::disk('public')->assertExists($post->fresh()->image);
    }

    public function test_image_is_deleted_when_post_is_deleted(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg');

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'image' => $file->store('posts', 'public'),
        ]);

        $imagePath = $post->image;

        $this->actingAs($user)->delete(route('posts.destroy', $post));

        Storage::disk('public')->assertMissing($imagePath);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
