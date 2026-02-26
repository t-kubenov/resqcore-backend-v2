<?php

namespace App\FieldOption\Handlers;

use App\Tenant\User;
use App\FieldOption\Contracts\FieldOptionHandler;
use App\FieldOption\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CreatedByFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'created_by';
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $context = null): LengthAwarePaginator
    {
        $companyId = $context->companyId;

        $query = User::
            select([
                'id', \DB::raw("CONCAT(last_name, ' ', first_name, ' ', patronymic) as name")
            ])
            ->whereHas('branch_office', function ($query) use ($companyId) {
                $query->where('rescue_company_id', $companyId);
            });

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
