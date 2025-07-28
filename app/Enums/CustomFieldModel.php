<?php

namespace App\Enums;

use App\Models\V2\Report\Report;
use App\Models\V2\Report\Update;
use App\Traits\EnumToArray;

enum CustomFieldModel: string
{
    use EnumToArray;

    case Report = Report::class;
    case Update = Update::class;
}
