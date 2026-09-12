<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    public function __construct(
        public User $commenter,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'post_commented',
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'commenter_username' => $this->commenter->username,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'message' => "{$this->commenter->name} commented on your post \"{$this->post->title}\"",
            'url' => route('posts.show', $this->post),
        ];
    }
}
