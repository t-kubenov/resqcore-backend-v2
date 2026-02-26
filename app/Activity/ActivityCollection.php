<?php

namespace App\Activity;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ActivityCollection extends ResourceCollection
{
    public $collects = ActivityResource::class;
}
