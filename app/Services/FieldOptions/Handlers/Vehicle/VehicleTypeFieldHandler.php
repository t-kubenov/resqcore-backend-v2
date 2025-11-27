<?php

namespace App\Services\FieldOptions\Handlers\Vehicle;

use App\Models\Vehicle\VehicleType;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VehicleTypeFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'vehicle_type_id';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $query = VehicleType::where('rescue_company_id', $companyContext->companyId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
