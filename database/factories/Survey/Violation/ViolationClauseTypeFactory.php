<?php

namespace Database\Factories\Survey\Violation;

use App\Survey\Violation\ViolationClauseType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ViolationClauseType>
 */
class ViolationClauseTypeFactory extends Factory
{
    protected $model = ViolationClauseType::class;

    public function definition(): array
    {
        return [
            'key' => fake()->word(),
            'name' => fake()->word(),
            'alias' => fake()->word(),
            'link' => fake()->url(),
        ];
    }
}
