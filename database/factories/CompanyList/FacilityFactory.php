<?php

namespace Database\Factories\CompanyList;

use App\CompanyList\Company;
use App\CompanyList\Facility;
use App\Tenant\BranchOffice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition(): array
    {
        return [
            'owner' => Company::inRandomOrder()->first()->id,
            'location' => $this->faker->address,
            'overseeing_branch' => BranchOffice::inRandomOrder()->first()->id,
        ];
    }
}
