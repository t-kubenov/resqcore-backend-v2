<?php

namespace Database\Factories\V2\Survey;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SurveyFacilitySpokesmanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'survey_id' => null, // This will be set when creating the survey
            'name' => $this->faker->name(),
        ];
    }
}
