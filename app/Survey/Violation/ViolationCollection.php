<?php

namespace App\Survey\Violation;

use App\Document\Http\GenericCollection;

class ViolationCollection extends GenericCollection
{
    protected string $modelName = Violation::class;

    public $collects = ViolationResource::class;
}
