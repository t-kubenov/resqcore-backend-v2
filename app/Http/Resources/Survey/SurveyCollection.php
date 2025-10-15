<?php

namespace App\Http\Resources\Survey;

use App\Http\Resources\GenericCollection;
use App\Http\Resources\GenericRelationshipResource;
use App\Models\Survey\Survey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SurveyCollection extends GenericCollection
{
    protected string $modelName = Survey::class;
}
