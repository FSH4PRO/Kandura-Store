<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;

class UserPolicy
{
    
    public function viewAny(Admin $admin): bool
    {
        if ($admin->hasRole(['super_admin', 'manage_users'])) {
            return true;
        }

        return $admin->can('users.view');
    }

   
   public function viewAdmin(Admin $admin, User $target): bool
    {
        if($admin->hasAnyRole(['super_admin','manage_admins'])) {
            return true;
        }
        return $admin->can('admins.view');
    }

       public function createAdmin(Admin $admin): bool
    {
       
        return $admin->hasRole('super_admin');
    }

   
    public function delete(Admin $admin, User $target): bool
    {
       
        if ($target->usable_type === Admin::class && $target->usable_id === $admin->id) {
            return false;
        }

        if ($admin->hasRole('super_admin')) {
            return true;
        }

        return $admin->can('users.delete');
    }
}
