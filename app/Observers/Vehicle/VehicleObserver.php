<?php

namespace App\Observers\Vehicle;

use App\Models\Vehicle\Vehicle;

class VehicleObserver
{
    public function creating(Vehicle $vehicle): void
    {
        $vehicle->branch_office_id ??= auth()->user()->branch_id;
    }
}
