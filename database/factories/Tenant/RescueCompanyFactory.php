<?php

namespace Database\Factories\Tenant;

use App\Tenant\RescueCompany;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends Factory<RescueCompany>
 */
class RescueCompanyFactory extends Factory
{
    protected $model = RescueCompany::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'alias' => $this->faker->word,
            'key' => Str::random(16),
        ];
    }
}
