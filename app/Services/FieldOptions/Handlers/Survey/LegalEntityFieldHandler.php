<?php

namespace App\Services\FieldOptions\Handlers\Survey;

use App\Models\Survey\SurveyLegalEntity;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LegalEntityFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'legal_entity';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $query = SurveyLegalEntity::where('branch_office_id', $companyContext->branchId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
