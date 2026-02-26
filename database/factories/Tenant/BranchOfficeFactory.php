<?php

namespace Database\Factories\Tenant;

use App\Tenant\BranchOffice;
use App\Tenant\RescueCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BranchOffice>
 */
class BranchOfficeFactory extends Factory
{
    protected $model = BranchOffice::class;

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
