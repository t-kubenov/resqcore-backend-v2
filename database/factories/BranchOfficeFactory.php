<?php

namespace Database\Factories;

use App\Models\RescueCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BranchOffice>
 */
class BranchOfficeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Филиал города ' . $this->faker->city(),
            'rescue_company_id' => RescueCompany::factory(),
            'key' => $this->faker->unique()->word,
            'alias' => $this->faker->unique()->word,
            'is_central_office' => false,
        ];
    }

    public function central_office(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_central_office' => true,
        ]);
    }
}
