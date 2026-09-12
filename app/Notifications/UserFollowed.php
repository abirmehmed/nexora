<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    public function __construct(
        public User $follower
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'user_followed',
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_username' => $this->follower->username,
            'message' => "{$this->follower->name} started following you",
            'url' => route('profile.show', $this->follower->username),
        ];
    }
}
