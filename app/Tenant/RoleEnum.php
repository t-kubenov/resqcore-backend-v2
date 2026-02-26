<?php

namespace App\Tenant;

enum RoleEnum: string
{
    case Admin = 'admin';
    case CentralOfficeUser = 'central_office_user';
    case BranchOfficeUser = 'branch_office_user';
}
