<?php

namespace App\Providers;

use App\Image\Image;
use App\Report\Report;
use App\Report\Update\Update;
use App\Survey\Models\Survey;
use App\Survey\Violation\Violation;
use App\Tenant\BranchOffice;
use App\Tenant\RescueCompany;
use App\Tenant\User;
use App\Vehicle\Vehicle;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        require_once app_path('../bootstrap/helpers.php');

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

        Relation::enforceMorphMap([
            'user' => User::class,
            'rescue_company' => RescueCompany::class,
            'branch_office' => BranchOffice::class,
            'report' => Report::class,
            'update' => Update::class,
            'survey' => Survey::class,
            'violation' => Violation::class,
            'vehicle' => Vehicle::class,
            'image' => Image::class,
        ]);
    }
}
