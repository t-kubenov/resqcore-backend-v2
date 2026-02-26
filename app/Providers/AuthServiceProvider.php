<?php

namespace App\Providers;

use App\Report\Report;
use App\Report\ReportPolicy;
use App\Report\Update\Update;
use App\Report\Update\UpdatePolicy;
use App\Survey\Models\Survey;
use App\Survey\SurveyPolicy;
use App\Survey\Violation\Violation;
use App\Vehicle\Vehicle;
use App\Vehicle\VehiclePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Report::class => ReportPolicy::class,
        Update::class => UpdatePolicy::class,
        Survey::class => SurveyPolicy::class,
        Violation::class => SurveyPolicy::class,
        Vehicle::class => VehiclePolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
