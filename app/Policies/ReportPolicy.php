<?php

namespace App\Policies;

use App\Enums\AccessLevel;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class ReportPolicy
{
    public function show(User $user, Report $report): Response
    {
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
