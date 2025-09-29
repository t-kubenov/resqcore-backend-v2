<?php

namespace App\Http\Controllers;

use App\Enums\CustomFieldParameter;
use App\Http\Resources\GenericRelationshipCollection;
use App\Models\Egov\MineralField;
use App\Models\Egov\OilField;
use App\Models\Egov\SubsoilContract;
use App\Models\Report\Report;
use App\Models\Report\Update;
use App\Models\Survey\Survey;
use App\Models\Survey\Violation;
use App\Models\Sushi\ReportFieldCategory;
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
