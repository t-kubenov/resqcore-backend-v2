<?php

namespace App\Survey\Http;

use App\Document\Http\DocumentCollection;
use App\Survey\Models\Survey;

class SurveyCollection extends DocumentCollection
{
    protected string $modelName = Survey::class;

    public $collects = SurveyResource::class;
}
