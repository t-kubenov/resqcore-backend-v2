<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CustomFieldModel: string
{
    use EnumToArray;

    case Report = 'Report';
    case Update = 'Update';
}
