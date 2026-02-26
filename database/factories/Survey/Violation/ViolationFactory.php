<?php

namespace Database\Factories\Survey\Violation;

use App\Survey\Models\Survey;
use App\Survey\Violation\Violation;
use App\Survey\Violation\ViolationClauseType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Violation>
 */
class ViolationFactory extends Factory
{
    protected $model = Violation::class;

    public function definition(): array
    {
        return [
            'survey_id' => Survey::inRandomOrder()->first()->id,
            'description' => $this->faker->text(200),
            'clause_type_id' => ViolationClauseType::factory(),
            'clause' => $this->faker->text(50),
            'elimination_prescription_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'elimination_completion' => $this->faker->boolean(),
            'elimination_completion_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
