<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    
    protected function getAuthUserId($authUser): ?int
    {
        if ($authUser instanceof User) {
            return $authUser->id;
        }

        if (method_exists($authUser, 'user') && $authUser->user) {
            return $authUser->user->id;
        }

        return null;
    }

    
    public function view($authUser, User $user): bool
    {
        $authUserId = $this->getAuthUserId($authUser);

        return $authUserId !== null && $authUserId === $user->id;
    }

       public function update($authUser, User $user): bool
    {
        $authUserId = $this->getAuthUserId($authUser);

        return $authUserId !== null && $authUserId === $user->id;
    }

   
    public function delete($authUser, User $targetUser): bool
    {
        $authUserId = $this->getAuthUserId($authUser);

        
        if ($authUserId === null || $authUserId === $targetUser->id) {
            return false;
        }

        
        if (!method_exists($authUser, 'hasRole') || ! $authUser->hasRole('super_admin')) {
            return false;
        }

        
        if ($targetUser->hasRole('super_admin')) {
            return false;
        }

        return true;
    }

   
    public function createAdmin($authUser): bool
    {
        return method_exists($authUser, 'hasRole')
            && $authUser->hasRole('super_admin');
    }
}
