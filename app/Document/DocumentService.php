<?php

namespace App\Document;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

abstract class DocumentService
{
    public function throttle(Request $request): void
    {
        if (config('app.debug')) {
            return;
        }

        $key = 'create:' . (auth()->user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        RateLimiter::hit($key, 5);
    }

    public function filterDates(Builder $query, string $field, string $dates): void
    {
        [$startDate, $endDate] = explode('|', $dates);

        if ($startDate) {
            $query->whereDate($field, '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate($field, '<=', $endDate);
        }
    }

    protected function filterByRoles($query) //todo: rename to scopeForUser
    {
        $user = auth()->user();

        if ($user->isBranchOffice()) {
            $query->where('branch_office_id', $user->branch_id);
        }

        if ($user->isCentralOffice()){
            $query->whereHas('branch_office', function($q) use ($user){
                $q->where('rescue_company_id', $user['rescue_company_id']);
            });
        }

        return $query;
    }
}
