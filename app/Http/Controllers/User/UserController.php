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
        $customer = auth('customer')->user();
        $user = $this->service->getProfile($customer->user);

        return $this->success(new UserResource($user), __('messages.user_profile'));
    }

    public function update(UpdateUserRequest $request)
    {
        $customer = auth('customer')->user();
        $user = $customer->user;

        $this->authorize('update', $user);

        $data = $request->validated();
        $profileImage = $request->file('profile_image');

        $updatedUser = $this->service->updateProfile($user, $data, $profileImage);

        return $this->success(new UserResource($updatedUser), __('messages.user_update'));
    }
}
