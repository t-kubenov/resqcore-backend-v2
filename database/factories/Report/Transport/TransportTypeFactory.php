<?php

namespace Database\Factories\Report\Transport;

use App\Report\Transport\TransportType;
use App\Tenant\RescueCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransportType>
 */
class TransportTypeFactory extends Factory
{
    protected $model = TransportType::class;

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
