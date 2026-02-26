<?php

namespace Database\Factories\Survey\Models;

use App\Survey\Models\SurveyLegalEntityUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyLegalEntityUnit>
 */
class SurveyLegalEntityUnitFactory extends Factory
{
    protected $model = SurveyLegalEntityUnit::class;

    public function definition(): array
    {
        return [
            'legal_entity_id' => \App\Survey\Models\SurveyLegalEntity::inRandomOrder()->first()->id,
            'name' => $this->faker->company(),
        ];
    }
}
