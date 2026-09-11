<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateUserProfile
{
    public function execute(User $user, array $data): User
    {
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $data['avatar'] = $this->storeImage($data['avatar'], 'avatars', $user->avatar);
        }

        if (isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            $data['cover_image'] = $this->storeImage($data['cover_image'], 'covers', $user->cover_image);
        }

        $user->update($data);

        return $user->fresh();
    }

    private function storeImage(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $file->store($directory, 'public');
    }
}
