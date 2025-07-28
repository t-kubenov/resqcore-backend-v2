<?php

namespace App\Policies;

use App\Enums\AccessLevel;
use App\Models\User;
use App\Models\V2\Report\Report;

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
