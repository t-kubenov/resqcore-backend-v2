<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Report\Report;
use App\Models\Survey\Survey;

class GeneralPolicy
{
    public function view(User $user, Report|Survey $report): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCentralOffice()) {
            return $user->rescue_company_id === $report->branch_office->rescue_company_id;
        }

        if ($user->isBranchOffice()) {
            return $user->branch_id === $report->branch_office_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isBranchOffice() || $user->isAdmin();
    }
}
