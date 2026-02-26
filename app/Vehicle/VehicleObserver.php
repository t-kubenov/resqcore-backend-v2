<?php

namespace App\Vehicle;

class VehicleObserver
{
    public function creating(Vehicle $vehicle): void
    {
        $vehicle->branch_office_id ??= auth()->user()->branch_id;
    }
}
