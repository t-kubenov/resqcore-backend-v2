<?php

namespace App\FieldOption\Handlers\Report;

use App\Report\Transport\TransportType;
use App\FieldOption\Contracts\FieldOptionHandler;
use App\FieldOption\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransportTypeFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'transport_type';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null): LengthAwarePaginator
    {
        $companyId = auth()->user()->rescue_company_id;

        $query = TransportType::where('rescue_company_id', $companyId);

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
