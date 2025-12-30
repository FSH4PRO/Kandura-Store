<?php

namespace App\Services\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Role::query()
            ->where('guard_name', 'admin');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        return $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
    }

    
    public function getAllPermissions(): Collection
    {
        return Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get();
    }

    public function getGroupedPermissions()
    {
       
        $permissions = Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get();

       
        return $permissions->groupBy(function ($perm) {
            return explode('.', $perm->name)[0] ?? 'other';
        });
    }

    
    public function create(array $data): Role
    {
        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => 'admin',
        ]);

        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->load('permissions');
    }

   
    public function update(Role $role, array $data): Role
    {
        $role->update([
            'name' => $data['name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return $role->fresh()->load('permissions');
    }

   
    public function delete(Role $role): void
    {
        $role->delete();
    }

    
    public function getRolePermissionNames(Role $role): array
    {
        return $role->permissions
            ->pluck('name')
            ->toArray();
    }
}
