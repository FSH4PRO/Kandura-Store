<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{

    public function listUsers(User $currentUser, array $filters = []): LengthAwarePaginator
    {
        $query = User::query()
            ->with(['usable' => function ($morph) {
                $morph->withTrashed();
            }])
            ->where('usable_type', Customer::class);

        $query
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null)
            ->sort($filters['sort_by'] ?? null, $filters['sort_dir'] ?? null);

        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        return $query->paginate($perPage)->withQueryString();
    }


    public function listAdmins(array $filters = []): LengthAwarePaginator
    {
        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        $query = User::query()
            ->with([
                'usable' => function ($q) {
                    $q->with('roles');
                },
            ])
            ->where('usable_type', Admin::class)->whereNot('id', 1);


        if (! empty($filters['role'])) {
            $roleName = $filters['role'];

            $query->whereHas('usable', function ($q) use ($roleName) {
                $q->whereHas('roles', function ($qr) use ($roleName) {
                    $qr->where('name', $roleName);
                });
            });
        }

        $query
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null)
            ->sort($filters['sort_by'] ?? null, $filters['sort_dir'] ?? null);

        return $query->paginate($perPage)->withQueryString();
    }


    public function createAdmin(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $admin = Admin::create([
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $name = $data['name'] ?? [];

            $user = User::create([
                'name' => [
                    'en' => $name['en'] ?? '',
                    'ar' => $name['ar'] ?? ($name['en'] ?? ''),
                ],
                'is_active'   => $data['is_active'] ?? true,
                'usable_id'   => $admin->id,
                'usable_type' => Admin::class,
            ]);


            if (! empty($data['roles']) && method_exists($admin, 'syncRoles')) {
                $admin->syncRoles($data['roles']);
            }

            return $user->load('usable');
        });
    }


    public function updateAdminRoles(User $user, array $roles): User
    {
        return DB::transaction(function () use ($user, $roles) {

            $admin = $user->usable;

            if (! $admin instanceof Admin) {
                throw new \RuntimeException('This user is not an admin.');
            }


            $roles = array_filter($roles);


            if (method_exists($admin, 'syncRoles')) {
                $admin->syncRoles($roles);
            }

            return $user->fresh()->load('usable');
        });
    }


    public function deleteUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            $usable = $user->usable;

            if ($usable) {
                $usable->delete();
            }

            $user->delete();
        });
    }


    public function deleteAdmin(User $user): void
    {
        DB::transaction(function () use ($user) {
            $admin = $user->usable;

            if ($admin instanceof Admin) {
                $admin->delete();
            }

            $user->delete();
        });
    }
}
