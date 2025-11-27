<?php

namespace App\Services\FieldOptions\Handlers\Survey;

use App\Models\Survey\ViolationClauseType;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
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
