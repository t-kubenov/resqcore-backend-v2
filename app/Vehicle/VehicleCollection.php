<?php

namespace App\Vehicle;

use App\Document\Http\GenericCollection;

class VehicleCollection extends GenericCollection
{
    protected string $modelName = Vehicle::class;

    public $collects = VehicleResource::class;
}
