<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct()
    {
        //
    }

   
    public function getProfile(User $user): User
    {
        return $user;
    }

    
    public function updateProfile(User $user, array $data, ?UploadedFile $profileImage = null): User
    {
        return DB::transaction(function () use ($user, $data, $profileImage) {

            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            
            unset($data['is_active'], $data['role']);

            
            if (isset($data['name'])) {
                // يا إمّا تعتمد اللغة الحالية:
                // $user->setTranslation('name', app()->getLocale(), $data['name']);
                // أو لو مكتفي بـ string عادية:
                $user->name = $data['name'];
                unset($data['name']);
            }

           
            if (! empty($data)) {
                $user->fill($data);
            }

            $user->save();

         
            if ($profileImage) {
                $user
                    ->addMedia($profileImage)
                    ->toMediaCollection('profile_image');
            }

            return $user->fresh();
        });
    }
}
