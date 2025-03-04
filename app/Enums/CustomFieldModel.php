<?php

namespace App\Enums;

use App\Models\Report;
use App\Models\Update;
use App\Traits\EnumToArray;

enum CustomFieldModel: string
{
    use EnumToArray;

    case Report = Report::class;
    case Update = Update::class;
}
