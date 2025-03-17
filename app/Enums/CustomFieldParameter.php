<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CustomFieldParameter: string
{
    use EnumToArray;

    case IsTotalField = 'is_total';
    case HasTotal = 'has_total';
    case IsFilterable = 'is_filterable';
    case IncludeToUpdates = 'include_to_updates';

}
