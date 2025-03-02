<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CustomFieldParameter: string
{
    use EnumToArray;

    case IsTotalField = 'is_total';
    case IsRequired = 'is_required';
    case IsFilterable = 'is_filterable';
    case HasTotal = 'has_total';

}
