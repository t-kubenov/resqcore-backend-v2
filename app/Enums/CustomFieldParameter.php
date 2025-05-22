<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CustomFieldParameter: string
{
    use EnumToArray;

    case IsTotalField = 'is_total';
    case HasTotal = 'has_total';
    case IsFilterable = 'is_filterable';
    case IsFilterableForCA = 'is_filterable_for_ca';
    case IsRequired = 'is_required';
    case IncludeToUpdates = 'include_to_updates';
    case IsHidden = 'is_hidden';    // hidden from the creation form, that is
    case IsCustom = 'is_custom';
    case CategoryId = 'category_id';
    case ShowInTable = 'show_in_table';
}
