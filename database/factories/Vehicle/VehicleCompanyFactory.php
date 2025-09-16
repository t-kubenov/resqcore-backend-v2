<?php

namespace Database\Factories\Vehicle;

use App\Models\RescueCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle\VehicleCompany>
 */
class VehicleCompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->word(),
            'name'=> $this->faker->sentence(),
            'rescue_company_id' => RescueCompany::factory(),
        ];
    }
}
