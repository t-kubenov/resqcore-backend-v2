<?php

namespace App\Filter\Filters;

use App\Filter\Contracts\FilterStrategy;

abstract class BaseFilter implements FilterStrategy
{
    public function __construct(protected string $column) {}
}
