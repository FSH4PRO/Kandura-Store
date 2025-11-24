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
            ->with(['roles', 'usable'])
            ->where('usable_type', Admin::class);


        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
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

            // إنشاء admin record
            $admin = Admin::create([
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                // لو عندك عمود phone في جدول admins اضيفه هون:
                // 'phone'    => $data['phone'] ?? null,
            ]);

            // تجهيز الاسم المترجم
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

            // ربط الرولات المختارة (micro-roles)
            if (! empty($data['roles']) && method_exists($user, 'syncRoles')) {
                $user->syncRoles($data['roles']);
            }

            return $user->load('usable', 'roles');
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
            $user->usable->delete();
            $user->delete();
        });
    }
}
