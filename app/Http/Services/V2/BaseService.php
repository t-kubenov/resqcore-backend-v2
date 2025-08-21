<?php

namespace App\Http\Services\V2;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;


abstract class BaseService
{
    public function throttle(Request $request): void // maybe: move to middleware
    {
        if (config('app.debug')) {
            return;
        }

        $key = 'survey-create:' . (auth()->user()?->id ?: $request->ip());

        if (RateLimiter::tooManyAttempts($key, 1)) {
            throw new ThrottleRequestsException(__('throttle.report-create'));
        }

        RateLimiter::hit($key, 60);
    }
}
