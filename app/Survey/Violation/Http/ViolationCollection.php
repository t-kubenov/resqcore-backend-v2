<?php

namespace App\Survey\Violation\Http;

use App\Document\Http\DocumentCollection;
use App\Survey\Violation\Violation;

class ViolationCollection extends DocumentCollection
{
    protected string $modelName = Violation::class;

    public $collects = ViolationResource::class;
}
