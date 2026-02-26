<?php

namespace App\Filter;

use Illuminate\Routing\Controller;
use App\Report\Report;
use App\Survey\Models\Survey;
use Illuminate\Http\JsonResponse;

class FilterController extends Controller
{
    public function getReportFilters(): JsonResponse
    {
        return response()->json(['data' => Report::filters()]);
    }

    public function getSurveyFilters(): JsonResponse
    {
        return response()->json(['data' => Survey::filters()]);
    }
}
