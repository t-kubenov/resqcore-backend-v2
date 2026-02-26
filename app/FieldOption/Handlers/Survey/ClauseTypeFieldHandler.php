<?php

namespace App\FieldOption\Handlers\Survey;

use App\FieldOption\Contracts\FieldOptionHandler;
use App\Survey\Violation\ViolationClauseType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClauseTypeFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName): bool
    {
        return $fieldName === 'clause_type';
    }

    public function getOptions(?string $search = null): LengthAwarePaginator
    {
        $query = ViolationClauseType::query();

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        return $query->paginate();
    }
}
