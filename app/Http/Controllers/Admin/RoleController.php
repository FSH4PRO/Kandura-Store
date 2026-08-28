<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;

class RoleController extends Controller
{
    protected RoleService $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
        ];

        $roles = $this->service->list($filters);

        return view('content.roles.index', compact('roles', 'filters'));
    }


    public function create()
    {
        $groupedPermissions = $this->service->getGroupedPermissions();

        return view('content.roles.create', compact('groupedPermissions'));
    }


    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();

        $this->service->create($data);

        return redirect()
            ->route('roles.index')
            ->with('success', __('roles.messages.created'));
    }


    public function edit(Role $role)
    {
        if ($role->guard_name !== 'admin') {
            abort(404);
        }

        $groupedPermissions  = $this->service->getGroupedPermissions();
        $rolePermissionNames = $this->service->getRolePermissionNames($role);

        return view('content.roles.edit', compact(
            'role',
            'groupedPermissions',
            'rolePermissionNames'
        ));
    }


    public function update(UpdateRoleRequest $request, Role $role)
    {
        if ($role->guard_name !== 'admin') {
            abort(404);
        }

        $data = $request->validated();

        $this->service->update($role, $data);

        return redirect()
            ->route('roles.index')
            ->with('success', __('roles.messages.updated'));
    }


    public function destroy(Role $role)
    {
        if ($role->guard_name !== 'admin') {
            abort(404);
        }


        if (in_array($role->name, ['super_admin'])) {
            return redirect()
                ->route('roles.index')
                ->with('error', __('roles.messages.not_deletable'));
        }

        $this->service->delete($role);

        return redirect()
            ->route('roles.index')
            ->with('success', __('roles.messages.deleted'));
    }
}
