<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCommented;
use App\Notifications\PostLiked;
use App\Notifications\UserFollowed;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create main test user
        $user1 = User::factory()->create([
            'name' => 'Alex Developer',
            'username' => 'alexdev',
            'email' => 'alex@nexora.test',
            'password' => bcrypt('password'),
            'bio' => 'Full-stack developer building cool things.',
        ]);

        // Create other users
        $user2 = User::factory()->create(['name' => 'Sarah Designer', 'username' => 'sarahdesigns']);
        $user3 = User::factory()->create(['name' => 'John Manager', 'username' => 'johnpm']);
        $user4 = User::factory()->create(['name' => 'Emily Writer', 'username' => 'emilywrites']);

        // Social Graph: user2, user3, user4 follow user1
        $user2->following()->attach($user1->id);
        $user3->following()->attach($user1->id);
        $user4->following()->attach($user1->id);
        $user1->notify(new UserFollowed($user2));
        $user1->notify(new UserFollowed($user3));

        // Posts
        $post1 = Post::factory()->create([
            'user_id' => $user1->id,
            'title' => 'Just launched my new Laravel project!',
            'body' => 'Excited to share that Nexora is finally taking shape. Built with Laravel 11, Tailwind CSS, and Alpine.js. #laravel #php',
        ]);

        $post2 = Post::factory()->create([
            'user_id' => $user2->id,
            'title' => 'The importance of whitespace in UI design',
            'body' => 'Whitespace is not empty space; it is an active design element. It helps guide the user\'s eye and improves readability significantly.',
        ]);

        $post3 = Post::factory()->create([
            'user_id' => $user3->id,
            'title' => 'Sprint planning tips',
            'body' => 'Always break down tasks into smaller, manageable chunks. It makes estimation easier and keeps the team motivated.',
        ]);

        // Likes
        Like::create(['user_id' => $user2->id, 'likeable_id' => $post1->id, 'likeable_type' => Post::class]);
        Like::create(['user_id' => $user3->id, 'likeable_id' => $post1->id, 'likeable_type' => Post::class]);
        Like::create(['user_id' => $user4->id, 'likeable_id' => $post1->id, 'likeable_type' => Post::class]);
        Like::create(['user_id' => $user1->id, 'likeable_id' => $post2->id, 'likeable_type' => Post::class]);
        
        // Notifications for likes
        $user1->notify(new PostLiked($user2, $post1));
        $user1->notify(new PostLiked($user3, $post1));

        // Comments
        $comment1 = Comment::create([
            'post_id' => $post1->id,
            'user_id' => $user2->id,
            'body' => 'Congratulations! The UI looks amazing. Can\'t wait to try it out.',
        ]);

        $comment2 = Comment::create([
            'post_id' => $post1->id,
            'user_id' => $user3->id,
            'body' => 'Great job! Let me know if you need help with project management features.',
        ]);

        // Notifications for comments
        $user1->notify(new PostCommented($user2, $post1));

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Login with: alex@nexora.test / password');
    }
}
