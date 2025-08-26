<?php

namespace Database\Factories\Survey;

use App\Models\Survey\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey\Violation>
 */
class ViolationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'survey_id' => Survey::inRandomOrder()->first()->id,
            'description' => $this->faker->text(200),
            'clause' => $this->faker->text(50),
            'elimination_prescription_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'elimination_completion' => $this->faker->boolean(),
            'elimination_completion_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
