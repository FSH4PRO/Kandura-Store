<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

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

    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where('guard_name', 'admin'),
            ],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],

        ]);



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

    
    public function update(Request $request, Role $role)
    {
        if ($role->guard_name !== 'admin') {
            abort(404);
        }

        $data = $request->validate([
            'name'          => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where('guard_name', 'admin')
                    ->ignore($role->id),
            ],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ],);

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
