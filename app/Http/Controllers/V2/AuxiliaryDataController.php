<?php

namespace App\Http\Controllers\V2;

use App\Enums\CustomFieldParameter;
use App\Models\BranchOffice;
use App\Models\Egov\MineralField;
use App\Models\Egov\OilField;
use App\Models\Egov\SubsoilContract;
use App\Models\EmergencyType;
use App\Models\Report;
use App\Models\TransportType;
use App\Models\Update;
use App\Models\V2\CustomField\CustomFieldOption;
use App\Models\V2\Sushi\DepartureType;
use App\Models\V2\Sushi\ReportFieldCategory;
use Illuminate\Http\JsonResponse;

class AuxiliaryDataController extends Controller
{
    public function getReportFilters(): JsonResponse
    {
        return response()->json(['data' => Report::filters()]);
    }

    public function getReportFields(): JsonResponse
    {
        $fields = Report::fieldDefinitions();

        return response()->json(['data' => $this->filterAndCategorizeFields($fields)]);
    }

    public function getUpdateFields(): JsonResponse
    {
        $fields = Update::fieldDefinitions();

        return response()->json(['data' => $this->filterAndCategorizeFields($fields)]);
    }

    private function filterAndCategorizeFields(array $fields): array
    {
        $fields = array_filter($fields, function ($field) {
            return !in_array(CustomFieldParameter::IsHidden, $field['parameters'] ?? []);
        });

        // Group fields by category
        $groupedFields = [];

        foreach ($fields as $field) {
            $category = ReportFieldCategory::find($field['parameters'][CustomFieldParameter::CategoryId->value] ?? 2);
            $catName = $category->name;

            if (!isset($groupedFields[$catName])) {
                $groupedFields[$catName] = [
                    'category' => $catName,
                    ...(isset($category->note) ? ['note' => $category->note] : []),
                    'fields' => []
                ];
            }

            $groupedFields[$catName]['fields'][] = $field;
        }

        return array_values($groupedFields);
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

        if ($fieldName === 'boolean') {
            return response()->json([
                'data' => [
                    ['id' => 1, 'name' => __('etc.boolean.true')],
                    ['id' => 0, 'name' => __('etc.boolean.false')],
                ]
            ], 200);
        }

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
