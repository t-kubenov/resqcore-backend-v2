<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Facility;
use App\Models\BranchOffice;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner' => Company::inRandomOrder()->first()->id,
            'location' => $this->faker->address,
            'overseeing_branch' => BranchOffice::inRandomOrder()->first()->id,
        ];
    }
}
