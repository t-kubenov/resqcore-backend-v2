<?php

namespace App\FieldOption\Handlers\Survey;

use App\FieldOption\Contracts\FieldOptionHandler;
use App\FieldOption\Support\FieldOptionContext;
use App\Survey\Models\SurveyLegalEntityUnit;
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
