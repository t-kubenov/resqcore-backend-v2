<?php

namespace App\FieldOption\Handlers\Vehicle;

use App\FieldOption\Contracts\FieldOptionHandler;
use App\FieldOption\Support\FieldOptionContext;
use App\Vehicle\VehicleCompany;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VehicleCompanyFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'vehicle_companies';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $query = VehicleCompany::where('rescue_company_id', $companyContext->companyId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
