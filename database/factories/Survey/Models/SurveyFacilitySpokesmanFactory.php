<?php

namespace Database\Factories\Survey\Models;

use App\Survey\Models\SurveyFacilitySpokesman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyFacilitySpokesman>
 */
class SurveyFacilitySpokesmanFactory extends Factory
{
    protected $model = SurveyFacilitySpokesman::class;

    public function definition(): array
    {
        return [
            'survey_id' => null, // This will be set when creating the survey
            'name' => $this->faker->name(),
        ];
    }
}
