<?php

namespace App\Report\Transport;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransportCollection extends ResourceCollection
{
    public $collects = TransportResource::class;
}
