<?php

namespace App\FieldOption\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface FieldOptionHandler
{
    public function supports(string $fieldName): bool;

    public function getOptions(?string $search = null): LengthAwarePaginator;
}
