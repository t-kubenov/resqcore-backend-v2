<?php

namespace App\Vehicle\Http;

use App\Document\Http\DocumentCollection;
use App\Vehicle\Vehicle;

class VehicleCollection extends DocumentCollection
{
    protected string $modelName = Vehicle::class;

    public $collects = VehicleResource::class;
}
