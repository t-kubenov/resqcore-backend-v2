<?php

namespace App\Enums;

enum AccessLevel: string
{
    case APP = 'app';
    case COMPANY = 'company';
    case BRANCH = 'branch_office';
}
