<?php

namespace Database\Factories\Survey\Models;

use App\Survey\Models\SurveySender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveySender>
 */
class SurveySenderFactory extends Factory
{
    protected $model = SurveySender::class;

    public function definition(): array
    {
        return [
            'survey_id' => null, // This will be set when creating the survey
            'name' => $this->faker->name(),
        ];
    }
}
