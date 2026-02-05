<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\User;

class UserPolicy
{

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('users.view');
    }

    public function view(Admin $admin, User $target): bool
    {
        return $admin->can('users.view');
    }



    public function viewAdmin(Admin $admin, User $target): bool
    {
        return $admin->can('admins.view');
    }

    public function createAdmin(Admin $admin): bool
    {

        return $admin->can('admins.create');
    }


    public function delete(Admin $admin, User $target): bool
    {

        if ($target->usable_type === Admin::class && $target->usable_id === $admin->id) {
            return false;
        }
        
        return $admin->can('users.delete');
    }



    public function update(Customer $customer, User $user): bool
    {
        return $customer->id === $user->usable_id && $user->usable_type === get_class($customer);
    }
}
