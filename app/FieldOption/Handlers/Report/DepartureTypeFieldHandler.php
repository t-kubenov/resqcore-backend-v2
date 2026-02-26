<?php

namespace App\FieldOption\Handlers\Report;

use App\Report\Sushi\DepartureType;
use App\FieldOption\Contracts\FieldOptionHandler;
use App\FieldOption\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DepartureTypeFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'departure_type';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $companyId = auth()->user()->rescue_company_id;

        $query = DepartureType::where('rescue_company_id', $companyId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
