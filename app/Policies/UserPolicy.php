<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
   
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }

    /**
     * المستخدم يحدّث نفسه بس
     * (لو حاب تسمح للـ admin يعدّل غيره كمان، منضيف && hasRole)
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }

}
