<?php

namespace App\Http\Controllers;

use App\Http\Resources\GenericRelationshipCollection;
use App\Models\Egov\MineralField;
use App\Models\Egov\OilField;
use App\Models\Egov\SubsoilContract;
use App\Models\Report\Report;
use App\Models\Survey\Survey;
use App\Services\FieldOptions\FieldOptionService;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuxiliaryDataController extends Controller
{
    public function __construct(
        private readonly FieldOptionService $service
    ){}

    public function getReportFilters(): JsonResponse
    {
        return response()->json(['data' => Report::filters()]);
    }

    public function getSurveyFilters(): JsonResponse
    {
        return response()->json(['data' => Survey::filters()]);
    }

    public function getMineralFields(): JsonResponse
    {
        $mineralFields = MineralField::orderBy('id')->paginate(15);

        return response()->json(['data' => $mineralFields], 200);
    }

    public function getOilFields(): JsonResponse
    {
        $oilFields = OilField::orderBy('id')->paginate(15);

        return response()->json(['data' => $oilFields], 200);
    }

    public function getSubsoilContracts(): JsonResponse
    {
        $subsoilContracts = SubsoilContract::orderBy('id')->paginate(15);

        return response()->json(['data' => $subsoilContracts], 200);
    }

    public function getFieldOptions(string $fieldName, Request $request)
    {
        $context = FieldOptionContext::fromAuth();
        $search = $request->input('search');

        if ($fieldName === 'boolean') {
            return response()->json([
                'data' => [
                    ['id' => 1, 'name' => __('etc.boolean.true')],
                    ['id' => 0, 'name' => __('etc.boolean.false')],
                ],
            ]);
        }

        $options = $this->service->getOptions($fieldName, $search, $context);

        return new GenericRelationshipCollection($options);
    }
}
