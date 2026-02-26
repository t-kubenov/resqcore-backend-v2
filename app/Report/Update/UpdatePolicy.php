<?php

namespace App\Report\Update;

use App\Tenant\User;
use App\Report\Report;

class UpdatePolicy
{
    public function create(User $user, Report $report): bool
    {
        if ($user->isBranchOffice()) {
            return $user->branch_id === $report->branch_office_id;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
