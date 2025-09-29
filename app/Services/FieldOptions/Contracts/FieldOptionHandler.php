<?php

namespace App\Services\FieldOptions\Contracts;

use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FieldOptionHandler
{
    public function supports(string $fieldName): bool;

    public function getOptions(?string $search = null): LengthAwarePaginator;
}
