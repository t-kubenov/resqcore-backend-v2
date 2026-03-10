<?php

namespace App\Vehicle;

use App\Document\Http\DocumentCollection;

class VehicleCollection extends DocumentCollection
{
    protected string $modelName = Vehicle::class;

    public $collects = VehicleResource::class;
}
