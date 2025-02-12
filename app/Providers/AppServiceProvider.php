<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perSecond(100)->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json(['message' => __('throttle.global')], 429);
                });
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(6)->by($request->ip())
                ->response(function () {
                    return response()->json(['message' => __('throttle.auth')], 429);
                });
        });

        RateLimiter::for('report-create', function (Request $request) {
            return Limit::perMinute(1)->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json(['message' => __('throttle.report-create')], 429);
                });
        });

        RateLimiter::for('update-create', function (Request $request) {
            return Limit::perMinute(1)->by($request->user()?->id ?: $request->ip())
            ->response(function () {
                return response()->json(['message' => __('throttle.update-create')], 429);
            });
        });

        RateLimiter::for('password-change', function (Request $request) {
            return Limit::perHour(1)->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json(['message' => __('throttle.password-change')], 429);
            });
        });

        RateLimiter::for('send-feedback', function (Request $request) {
            return Limit::perHour(1)->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json(['message' => __('throttle.send-feedback')], 429);
                });
        });
    }
}
