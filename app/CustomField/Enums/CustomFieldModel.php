<?php

namespace App\CustomField\Enums;

use App\Report\Report;
use App\Report\Update\Update;
use App\Document\Traits\EnumToArray;

enum CustomFieldModel: string
{
    use EnumToArray;

    case Report = Report::class;
    case Update = Update::class;
}
