<?php

namespace App\Services\FieldOptions\Handlers\Report;

use App\Models\EmergencyType;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
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


