<?php

namespace App\Filter\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterStrategy
{
    public function apply(Builder $query, mixed $value): void;
}
