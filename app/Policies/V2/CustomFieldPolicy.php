<?php

namespace App\Policies\V2;

use App\Models\User;

class CustomFieldPolicy
{
    public function create(User $user): bool
    {
        return $user->access_level === 'APP';
    }
}
