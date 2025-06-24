<?php

namespace App\Http\Controllers\V2;

use App\Enums\CustomFieldParameter;
use App\Http\Resources\V2\GenericRelationshipCollection;
use App\Http\Resources\V2\GenericRelationshipResource;
use App\Http\Resources\V2\GenericResource;
use App\Models\BranchOffice;
use App\Models\Egov\MineralField;
use App\Models\Egov\OilField;
use App\Models\Egov\SubsoilContract;
use App\Models\EmergencyType;
use App\Models\Model;
use App\Models\Report;
use App\Models\TransportType;
use App\Models\Update;
use App\Models\User;
use App\Models\V2\CustomField\CustomFieldOption;
use App\Models\V2\Survey\Survey;
use App\Models\V2\Survey\SurveyLegalEntity;
use App\Models\V2\Survey\SurveyLegalEntityUnit;
use App\Models\V2\Survey\Violation;
use App\Models\V2\Sushi\DepartureType;
use App\Models\V2\Sushi\ReportFieldCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuxiliaryDataController extends Controller
{
    public function getReportFilters(): JsonResponse
    {
        return response()->json(['data' => Report::filters()]);
    }

    public function getSurveyFilters(): JsonResponse
    {
        return response()->json(['data' => Survey::filters()]);
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

    public function getSurveyFields(): JsonResponse
    {
        $fields = Survey::fieldDefinitions();

        return response()->json(['data' => $this->filterAndCategorizeFields($fields)]);
    }

    public function getViolationFields()
    {
        $fields = Violation::fieldDefinitions();

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
                    'order' => $category->id,
                    ...(isset($category->note) ? ['note' => $category->note] : []),
                    'fields' => []
                ];
            }

            $groupedFields[$catName]['fields'][] = $field;
        }

        usort($groupedFields, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

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

    public function getFieldOptions(string $fieldName, Request $request) //todo: break this down
    {
        $companyId = auth()->user()->rescue_company_id;
        $branchId = auth()->user()->branch_id;
        $legalEntityId = $request->input('legal_entity_id', null);
        $search = $request->input('search', null);

        $models = [
            'branch_office' => BranchOffice::where('is_central_office', false)
                ->where('rescue_company_id', $companyId),
            'emergency_type' => EmergencyType::where('rescue_company_id', $companyId),
            'transport_type' => TransportType::where('rescue_company_id', $companyId),
            'departure_type' => DepartureType::where('rescue_company_id', $companyId),
            'legal_entity' => SurveyLegalEntity::where('branch_office_id', $branchId),
            'legal_entity_unit' => SurveyLegalEntityUnit::
                whereHas('legal_entity', function ($query) use ($branchId, $legalEntityId) {
                $query->where('branch_office_id', $branchId);
                if ($legalEntityId) {
                    $query->where('id', $legalEntityId);
                }
            }),
            'created_by' => User::withFullName()->
            whereHas('branch_office', function ($query) use ($companyId) {
                $query->where('rescue_company_id', $companyId);
            }),

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
            if ($search) {
                if ($fieldName == 'created_by') {
                    $models[$fieldName]->whereRaw("CONCAT(last_name, ' ', first_name, ' ', patronymic) ILIKE ?", ["%{$search}%"]);
                }
                else {
                    $models[$fieldName]->where('name', 'ILIKE', "%{$search}%");
                }
            }

            $data = $models[$fieldName];
        }

        else
        {
            $query = CustomFieldOption::whereHas('customField', function ($query) use ($fieldName, $companyId) {
                $query->where('name', $fieldName)
                    ->where('rescue_company_id', $companyId);
            });

            if ($search) {
                $query->where('label', 'ILIKE', "%{$search}%");
            }

            $paginated = $query->paginate();

            $transformed = $paginated->through(function ($item) {
                return [
                    'id' => $item->id,
                    'key' => $item->name,
                    'name' => $item->label,
                    'order' => $item->order,
                ];
            });

            return new GenericRelationshipCollection($transformed);
        }

        return new GenericRelationshipCollection($data->paginate());
    }
}
