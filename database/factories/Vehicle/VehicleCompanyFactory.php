<?php

namespace Database\Factories\Vehicle;

use App\Tenant\RescueCompany;
use App\Vehicle\VehicleCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleCompany>
 */
class VehicleCompanyFactory extends Factory
{
    protected $model = VehicleCompany::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->word(),
            'name'=> $this->faker->sentence(),
            'rescue_company_id' => RescueCompany::factory(),
        ];
    }
}
