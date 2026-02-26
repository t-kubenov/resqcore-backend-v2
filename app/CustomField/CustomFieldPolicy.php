<?php

namespace App\CustomField;

use App\Tenant\User;

class CustomFieldPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }
}
