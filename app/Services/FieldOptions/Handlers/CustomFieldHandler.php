<?php

namespace App\Services\FieldOptions\Handlers;

use App\Models\CustomField\CustomFieldOption;
use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomFieldHandler implements FieldOptionHandler
{
    public function supports(string $fieldName, ?int $rescueCompanyId = null): bool
    {
        return true;
    }

    public function getOptions(?string $search = null, ?FieldOptionContext $companyContext = null, ?string $fieldName = null): LengthAwarePaginator
    {
        $query = CustomFieldOption::whereHas('customField', function ($query) use ($fieldName, $companyContext) {
            $query->where('rescue_company_id', $companyContext->companyId)
                ->where('name', $fieldName);
        });

        if ($search) {
            $query->where('label', 'ILIKE', "%{$search}%");
        }

        return $query->paginate()->through(fn (CustomFieldOption $item) => [
            'id'    => $item->id,
            'key'   => $item->name,
            'name'  => $item->label,
        ]);
    }
}
