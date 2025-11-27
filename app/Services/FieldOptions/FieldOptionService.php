<?php

namespace App\Services\FieldOptions;

use App\Services\FieldOptions\Contracts\FieldOptionHandler;
use App\Services\FieldOptions\Support\FieldOptionContext;
use Illuminate\Pagination\LengthAwarePaginator;
use RuntimeException;

class FieldOptionService
{
    private iterable $handlers;

    /** @param iterable<FieldOptionHandler> $handlers */
    public function __construct(iterable $handlers)
    {
        $this->handlers = $handlers;
    }

    public function getOptions(string $fieldName, ?string $search, FieldOptionContext $context): LengthAwarePaginator
    {
        foreach ($this->handlers as $handler){
            if ($handler->supports($fieldName)) {
                return $handler->getOptions($search, $context, $fieldName);
            }
        }

        throw new RuntimeException("No handler found for field: {$fieldName}");
    }
}
