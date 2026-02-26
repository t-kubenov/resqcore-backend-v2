<?php

namespace App\FieldOption\Handlers\Report;

use App\Report\EmergencyType;
use App\FieldOption\Contracts\FieldOptionHandler;
use App\FieldOption\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmergencyTypeFieldHandler implements FieldOptionHandler
{

    public function supports(string $fieldName): bool
    {
        return $fieldName === 'emergency_type';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $companyId = auth()->user()->rescue_company_id;

        $query = EmergencyType::where('rescue_company_id', $companyId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}


