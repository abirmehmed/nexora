<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    public function __construct(
        public User $liker,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'post_liked',
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'liker_username' => $this->liker->username,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'message' => "{$this->liker->name} liked your post \"{$this->post->title}\"",
            'url' => route('posts.show', $this->post),
        ];
    }
}
