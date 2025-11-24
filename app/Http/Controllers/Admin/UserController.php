<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Services\Admin\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListUsersRequest;
use App\Http\Requests\Admin\StoreAdminRequest;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    public function index(ListUsersRequest $request)
    {
        $admin      = auth('admin')->user();
        $currentUser = $admin->user;

        $filters = $request->validated();

        $users = $this->service->listUsers($currentUser, $filters);

        $roles = collect();

        return view('content.users.index', [
            'users'   => $users,
            'roles'   => $roles,
            'filters' => $filters,
        ]);
    }


    public function adminsIndex(ListUsersRequest $request)
    {

        $filters = $request->validated();


        $admins = $this->service->listAdmins($filters);


        $roles = \Spatie\Permission\Models\Role::query()
            ->where('guard_name', 'user')
            ->pluck('name');

        return view('content.users.admin', [
            'admins'  => $admins,
            'filters' => $filters,
            'roles'   => $roles,
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->service->deleteUser($user);

        return back()->with('success', __('messages.user_deleted') ?? 'User deleted successfully.');
    }

    public function createAdmin()
    {
        $this->authorize('createAdmin', User::class);


        $roles = Role::query()
            ->where('guard_name', 'user')
            ->where('name', '!=', 'super_admin')
            ->pluck('name');

        return view('content.users.create-admin', [
            'roles' => $roles,
        ]);
    }


    public function storeAdmin(StoreAdminRequest $request)
    {
        $this->authorize('createAdmin', User::class);

        $admin = $this->service->createAdmin($request->validated());

        return redirect()
            ->route('admins.index')
            ->with('success', __('messages.admin_created'));
    }


    public function destroyAdmin(User $user)
    {
        $this->authorize('delete', $user);

        $this->service->deleteAdmin($user);

        return back()->with('success', __('messages.admin_deleted') ?? 'Admin user deleted successfully.');
    }
}
