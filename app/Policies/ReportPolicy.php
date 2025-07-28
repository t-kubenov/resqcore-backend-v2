<?php

namespace App\Policies;

use App\Enums\AccessLevel;
use App\Models\User;
use App\Models\V2\Report\Report;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    public function show(User $user, Report $report): Response
    {
//        dd($user->access_level, $report->branch_office->rescue_company_id, $user->branch_office->rescue_company_id, $report->branch_office_id, $user->branch_id);
        $match = match ($user->access_level) {
            AccessLevel::APP => true,
            AccessLevel::COMPANY => $report->branch_office->rescue_company_id === $user->branch_office->rescue_company_id,
            AccessLevel::BRANCH => $report->branch_office_id === $user->branch_id,
            default => false,
        };

        return $match? Response::allow() : Response::deny();
    }

    public function create(User $user): bool
    {
        return match ($user->access_level) {
            AccessLevel::APP, AccessLevel::BRANCH => true,
            default => false,
        };
    }
}
