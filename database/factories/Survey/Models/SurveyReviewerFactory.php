<?php

namespace Database\Factories\Survey\Models;

use App\Survey\Models\SurveyReviewer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyReviewer>
 */
class SurveyReviewerFactory extends Factory
{
    protected $model = SurveyReviewer::class;

    public function definition(): array
    {
        return [
            'survey_id' => null, // This will be set when creating the survey
            'name' => $this->faker->name(),
        ];
    }
}
