<?php

namespace App\Survey;

use App\Document\Http\GenericCollection;
use App\Survey\Models\Survey;

class SurveyCollection extends GenericCollection
{
    protected string $modelName = Survey::class;

    public $collects = SurveyResource::class;
}
