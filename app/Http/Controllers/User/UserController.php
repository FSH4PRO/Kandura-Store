<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    
    public function profile(Request $request)
    {
        $user = $this->service->getProfile($request->user('api'));

        return $this->success(new UserResource($user), 'User profile');
    }

    /**
     * PUT /api/users/{id}
     * تحديث بيانات المستخدم (self) + صورة
     */


    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);


        $data = $request->validated();
        $profileImage = $request->file('profile_image');

        $updatedUser = $this->service->updateProfile($user, $data, $profileImage);

        return $this->success(new UserResource($updatedUser), 'User updated successfully');
    }
}
