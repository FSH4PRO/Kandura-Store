<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
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
        $currentUser = auth('admin')->user()->user; // الـ User الصحيح

        $filters = $request->validated();

        $users = $this->service->listUsers($currentUser, $filters);


        $roleQuery = Role::query();

        if ($request->user()->hasRole('super_admin')) {

            $roleQuery->whereIn('name', ['admin', 'user']);
        } elseif ($request->user()->hasRole('admin')) {

            $roleQuery->where('name', 'user');
        } else {

            $roleQuery->whereRaw('1 = 0');
        }

        $roles = $roleQuery->pluck('name');

        return view('content.users.index', compact('users', 'roles', 'filters'));
    }



    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->service->deleteUser($user);

        return back()->with('success', 'user deleted successfully.');
    }

    public function createAdmin()
    {
        $this->authorize('createAdmin', User::class);

        return view('content.users.create-admin');
    }

    public function storeAdmin(StoreAdminRequest $request)
    {
        $this->authorize('createAdmin', User::class);

        $admin = $this->service->createAdmin($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'Admin user created successfully.');
    }
}
