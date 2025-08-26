<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report\Report;

class UpdatePolicy
{
    public function create(User $user, Report $report): bool
    {
        if ($user->isBranchOffice()) {
            return $user->branch_id === $report->branch_office_id;
        }

        return false;
    }
}
