<?php

namespace App\Http\Resources\Survey;

use App\Http\Resources\GenericCollection;
use App\Models\Survey\Violation;

class ViolationCollection extends GenericCollection
{
    protected string $modelName = Violation::class;
}
