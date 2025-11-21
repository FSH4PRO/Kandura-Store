<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * لستة المستخدمين مع فلترة حسب صلاحيات / دور المستخدم الحالي
     */
    public function listUsers(User $currentUser, array $filters = []): LengthAwarePaginator
    {
        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;

        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        $query = User::query()
            ->with('roles');

        /**
         * 🔒 التحكم بمن يشوف مين:
         *
         * - إذا currentUser super_admin:
         *    → يشوف بس (admins + users)
         *    → ما يشوف نفسه
         *
         * - إذا currentUser admin:
         *    → يشوف بس users
         *    → ما يشوف نفسه
         *
         * - غير هيك: ما يشوف حدا (احتياط)
         */
        if ($currentUser->hasRole('super_admin')) {
            $query
                ->where('id', '!=', $currentUser->id)
                ->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['admin', 'user']);
                });
        } elseif ($currentUser->hasRole('admin')) {
            $query
                ->where('id', '!=', $currentUser->id)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'user');
                });
        } else {
            // لو حدا بدون صلاحية حاول يدخل على صفحة المستخدمين
            $query->whereRaw('1 = 0');
        }

        // 🔍 سكوبات البحث والحالة والترتيب (من Model User)
        $query
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null)
            ->role($filters['role'] ?? null)
            ->sort($filters['sort_by'] ?? null, $filters['sort_dir'] ?? null);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }

   
    public function createAdmin(array $data): User
    {
        return DB::transaction(function () use ($data) {

          
            $admin = Admin::create([
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
            ]);

           
            $user = User::create([
                'name'        => [
                    'en' => $data['name_en'],
                    'ar' => $data['name_ar'] ?? $data['name_en'],
                ],
                'is_active'   => $data['is_active'] ?? true,
                'usable_id'   => $admin->id,
                'usable_type' => Admin::class,
            ]);


            if (method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }

            return $user->load('usable');
        });
    }

   
    public function deleteUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            $usable = $user->usable; // Admin أو Customer أو null

            if ($usable) {
                $usable->delete();
            }

            $user->delete();
        });
    }
}
