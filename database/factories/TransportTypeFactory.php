<?php

namespace Database\Factories;

use App\Models\RescueCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransportType>
 */
class TransportTypeFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->word;

        return [
            'name' => ucfirst($name),
            'key' => $name,
            'alias' => $this->faker->boolean(50) ? null: ucfirst($name),
            'rescue_company_id' => RescueCompany::inRandomOrder()->first()->id,
        ];
    }

    public function specifyCompany(int $rescueCompanyId): self
    {
        return $this->state([
            'rescue_company_id' => $rescueCompanyId,
        ]);
    }
}
