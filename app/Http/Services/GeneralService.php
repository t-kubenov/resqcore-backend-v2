<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;


abstract class GeneralService
{
    public function throttle(Request $request): void // maybe: move to middleware
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
}
