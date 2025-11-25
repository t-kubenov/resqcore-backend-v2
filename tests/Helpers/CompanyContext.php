<?php

namespace Tests\Helpers;

use App\Models\BranchOffice;
use App\Models\RescueCompany;
use App\Models\User;

final class CompanyContext
{
    public RescueCompany $rescueCompany;
    public BranchOffice $centralOffice;
    public BranchOffice $branchA;
    public BranchOffice $branchB;
    public User $branchUser;
    public User $centralUser;

    public function __construct()
    {
        $this->rescueCompany = RescueCompany::factory()->create();

        $this->centralOffice = BranchOffice::factory()->central_office()->for($this->rescueCompany, 'rescue_company')->create();
        $this->branchA = BranchOffice::factory()->for($this->rescueCompany, 'rescue_company')->create();
        $this->branchB = BranchOffice::factory()->for($this->rescueCompany, 'rescue_company')->create();

        $this->centralUser = User::factory()->central_office()->for($this->centralOffice, 'branch_office')->create();
        $this->branchUser = User::factory()->for($this->branchA, 'branch_office')->create();
    }
}
