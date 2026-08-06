<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getProfile(User $user): User
    {
        return $user->load('usable');
    }

    public function updateProfile(User $user, array $data, ?UploadedFile $profileImage = null): User
    {
        return DB::transaction(function () use ($user, $data, $profileImage) {

            $owner = $user->usable; 

            if (!empty($data['password'])) {
                $owner->password = Hash::make($data['password']);
                unset($data['password']);
            }

            if (isset($data['name'])) {
                $user->name = $data['name'];
            }

            if (isset($data['phone'])) {
                $owner->phone = $data['phone'];
            }

            $owner->save();
            $user->save();

            if ($profileImage) {
                $user->addMedia($profileImage)->toMediaCollection('profile_image');
            }

            return $user->fresh()->load('usable');
        });
    }
}
