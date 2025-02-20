<?php

namespace App\Policies;

use App\Enums\AccessLevel;
use App\Models\Report;
use App\Models\User;

class UpdatePolicy
{
    public function create(User $user, Report $report): bool
    {
        return match ($user['access_level']) {
            AccessLevel::APP => true,
            AccessLevel::BRANCH => $user->branch_id === $report->branch_office_id,
            default => false,
        };
    }
}
