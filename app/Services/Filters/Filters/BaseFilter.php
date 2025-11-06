<?php

namespace App\Services\Filters\Filters;

use App\Services\Filters\Contracts\FilterStrategy;

abstract class BaseFilter implements FilterStrategy
{
    public function __construct(protected string $column) {}
}
