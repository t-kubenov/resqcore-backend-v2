<?php

namespace Database\Factories\Vehicle;

use App\Tenant\RescueCompany;
use App\Vehicle\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleType>
 */
class VehicleTypeFactory extends Factory
{
    protected $model = VehicleType::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->word(),
            'name'=> $this->faker->sentence(),
            'rescue_company_id' => RescueCompany::factory(),
        ];
    }
}
