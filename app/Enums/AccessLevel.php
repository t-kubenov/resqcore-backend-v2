<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum AccessLevel: string
{
    use EnumToArray;

    case APP = 'app';
    case COMPANY = 'company';
    case BRANCH = 'branch_office';
}
