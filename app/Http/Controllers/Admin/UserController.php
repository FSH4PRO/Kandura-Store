<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Services\Admin\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Requests\Admin\ListUsersRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    public function index(ListUsersRequest $request)
    {
        $admin = auth('admin')->user();

        $this->authorize('viewAny', User::class);

        $currentUser = $admin->user ?? null;

        $filters = $request->validated();


        $users = $this->service->listUsers($currentUser ?? new User(), $filters);

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

        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->orderBy('name')
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

        return back()->with('success', __('messages.user_deleted'));
    }


    public function createAdmin()
    {
        $this->authorize('createAdmin', User::class);


        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->where('name', '!=', 'super_admin')
            ->orderBy('name')
            ->pluck('name');

        return view('content.users.create-admin', compact('roles'));
    }


    public function storeAdmin(StoreAdminRequest $request)
    {
        $this->authorize('createAdmin', User::class);

        $adminUser = $this->service->createAdmin($request->validated());

        return redirect()
            ->route('admins.index')
            ->with('success', __('messages.admin_created'));
    }


    public function editAdmin(User $user)
    {
        $this->authorize('createAdmin', User::class);


        if (! $user->usable instanceof \App\Models\Admin) {
            abort(404);
        }


        $adminModel = $user->usable;

        $roles = Role::query()
            ->where('guard_name', 'admin')
            ->where('name', '!=', 'super_admin')
            ->orderBy('name')
            ->pluck('name');

        $currentRoleNames = $adminModel->getRoleNames()->toArray();

        return view('content.users.edit-admin', [
            'user'             => $user,
            'adminModel'       => $adminModel,
            'roles'            => $roles,
            'currentRoleNames' => $currentRoleNames,
        ]);
    }


    public function updateAdmin(UpdateAdminRequest $request, User $user)
    {

        $this->authorize('createAdmin', User::class);

        if (! $user->usable instanceof \App\Models\Admin) {
            abort(404);
        }

        $data = $request->validated();

        $roles = $data['roles'] ?? [];

        $this->service->updateAdminRoles($user, $roles);

        return redirect()
            ->route('admins.index')
            ->with('success', __('messages.admin_updated'));
    }


    public function destroyAdmin(User $user)
    {
        $this->authorize('delete', $user);

        $this->service->deleteAdmin($user);

        return back()->with('success', __('messages.admin_deleted'));
    }

    public function getUserAddresses(User $user)
    {
        try {
            $this->authorize('view', $user);

            // Only customers have addresses
            if ($user->usable_type !== 'App\Models\Customer') {
                return response()->json(['addresses' => []]);
            }

            $addresses = $user->usable->addresses()->with('city')->get();

            return response()->json([
                'addresses' => AddressResource::collection($addresses)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __('messages.failed_operation')
            ], 500);
        }
    }

   
}
