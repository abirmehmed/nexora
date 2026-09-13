<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserMentioned extends Notification
{
    use Queueable;

    public function __construct(
        public User $mentioner,
        public Post $post
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'user_mentioned',
            'mentioner_id' => $this->mentioner->id,
            'mentioner_name' => $this->mentioner->name,
            'mentioner_username' => $this->mentioner->username,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
            'message' => "{$this->mentioner->name} mentioned you in a post",
            'url' => route('posts.show', $this->post),
        ];
    }
}
