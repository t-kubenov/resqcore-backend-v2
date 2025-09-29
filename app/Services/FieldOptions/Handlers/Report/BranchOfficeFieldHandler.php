<?php

namespace App\Services\FieldOptions\Handlers\Report;

use App\Models\BranchOffice;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BranchOfficeFieldHandler implements FieldOptionHandler
{

    public function supports(string $fieldName): bool
    {
        return $fieldName === 'branch_office';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $companyId = $companyContext->companyId;

        $query = BranchOffice::where('is_central_office', false)
            ->where('rescue_company_id', $companyId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
