<?php

namespace Database\Factories\Survey;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey\ViolationClauseType>
 */
class ViolationClauseTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
