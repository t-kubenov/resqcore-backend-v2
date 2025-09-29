<?php

namespace App\Services\FieldOptions\Handlers\Survey;

use App\Models\Survey\SurveyLegalEntityUnit;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LegalEntityUnitFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'legal_entity_unit';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $legalEntityId = $companyContext->legalEntityId;

        if (!$legalEntityId) {
            return SurveyLegalEntityUnit::whereRaw('1 = 0')->paginate();
        }

        $query = SurveyLegalEntityUnit::where('legal_entity_id', $legalEntityId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
