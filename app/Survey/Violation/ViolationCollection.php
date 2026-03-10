<?php

namespace App\Survey\Violation;

use App\Document\Http\DocumentCollection;

class ViolationCollection extends DocumentCollection
{
    protected string $modelName = Violation::class;

    public $collects = ViolationResource::class;
}
