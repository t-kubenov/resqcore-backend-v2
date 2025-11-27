<?php

namespace App\Http\Resources\Vehicle;

use App\Http\Resources\GenericCollection;
use App\Models\Vehicle\Vehicle;
use Illuminate\Http\Request;

class VehicleCollection extends GenericCollection
{
    protected string $modelName = Vehicle::class;
}
