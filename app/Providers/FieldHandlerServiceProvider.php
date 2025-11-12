<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FieldOptions\FieldOptionService;
use App\Services\FieldOptions\Handlers\{
    CreatedByFieldHandler,
    CustomFieldHandler,
    Report\BranchOfficeFieldHandler,
    Report\DepartureTypeFieldHandler,
    Report\EmergencyTypeFieldHandler,
    Report\TransportTypeFieldHandler,
    Survey\ClauseTypeFieldHandler,
    Survey\LegalEntityFieldHandler,
    Survey\LegalEntityUnitFieldHandler,
    Vehicle\VehicleCompanyFieldHandler,
    Vehicle\VehicleTypeFieldHandler,
    EnumFieldHandler,
};

class FieldHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->tag([
            BranchOfficeFieldHandler::class,
            DepartureTypeFieldHandler::class,
            EmergencyTypeFieldHandler::class,
            TransportTypeFieldHandler::class,
            ClauseTypeFieldHandler::class,
            LegalEntityFieldHandler::class,
            LegalEntityUnitFieldHandler::class,
            CreatedByFieldHandler::class,
            VehicleTypeFieldHandler::class,
            VehicleCompanyFieldHandler::class,
            EnumFieldHandler::class,

            CustomFieldHandler::class,
        ], 'handlers');

        $this->app->when(FieldOptionService::class)
            ->needs('$handlers')
            ->giveTagged('handlers');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
