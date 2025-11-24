<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admin;

class UserPolicy
{
    
    protected function actor($authUser): ?User
    {
      
        if ($authUser instanceof Admin) {
            return $authUser->user; 
        }

        
        if ($authUser instanceof User) {
            return $authUser;
        }

        return null;
    }

      public function createAdmin($authUser): bool
    {
        $actor = $this->actor($authUser);

        if (! $actor) {
            return false;
        }

        return $actor->hasRole('super_admin');
    }


    public function delete($authUser, User $target): bool
    {
        $actor = $this->actor($authUser);

        if (! $actor) {
            return false;
        }

        
        if ($actor->id === $target->id) {
            return false;
        }

        
        return $actor->can('users.delete');
    }

    public function viewAny($authUser): bool
    {
        $actor = $this->actor($authUser);

        if (! $actor) {
            return false;
        }

        return $actor->can('users.view');
    }



    
}
