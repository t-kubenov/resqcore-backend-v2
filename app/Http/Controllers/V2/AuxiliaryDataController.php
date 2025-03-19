<?php

namespace App\Http\Controllers\V2;

use App\Enums\CustomFieldParameter;
use App\Models\BranchOffice;
use App\Models\DepartureType;
use App\Models\Egov\MineralField;
use App\Models\Egov\OilField;
use App\Models\Egov\SubsoilContract;
use App\Models\EmergencyType;
use App\Models\Report;
use App\Models\TransportType;
use App\Models\Update;
use App\Models\V2\CustomField\CustomField;
use App\Models\V2\CustomField\CustomFieldOption;
use App\Models\V2\CustomField\CustomFieldValue;
use Illuminate\Http\JsonResponse;

class AuxiliaryDataController extends Controller
{
    public function getReportFilters(): JsonResponse
    {
        return response()->json(['data' => Report::filters()]);
    }

    public function getReportFields(): JsonResponse
    {
        $fields = array_filter(Report::fieldDefinitions(), function ($field) {
            return !in_array(CustomFieldParameter::IsHidden, $field['parameters'] ?? []);
        });

//        $data = array_map(fn($field) => [
//            'name' => $field['key'],
//            'label' => $field['name'],
//            'type' => $field['type'],
//            'options' => $field['options'] ?? null,
//            'validationRules' => [
//                'required' => in_array('required', $field['rules'] ?? [])
//            ],
//        ], $fields);

        return response()->json(['data' => $fields]);
    }

    public function getUpdateFields(): JsonResponse
    {
        return response()->json(['data' => Update::fieldDefinitions()]);
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

    public function getFieldOptions(string $fieldName): JsonResponse
    {
        $companyId = auth()->user()->rescue_company_id;

        $models = [
            'branch_office' => BranchOffice::class,
            'emergency_type' => EmergencyType::class,
            'transport_type' => TransportType::class,
            'departure_type' => DepartureType::class,
        ];

        if (array_key_exists($fieldName, $models))
        {
            $data = $models[$fieldName]::where('rescue_company_id', $companyId)->get();
        }

        else
        {
            $data = CustomFieldOption::whereHas('customField', function ($query) use ($fieldName, $companyId) {
                $query->where('name', $fieldName)
                    ->where('rescue_company_id', $companyId);
            })->get();
        }

        return response()->json(['data' => $data], 200);
    }
}
