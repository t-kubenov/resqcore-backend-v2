<?php

namespace Database\Factories\V2\Survey;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SurveyLegalEntityUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legal_entity_id' => \App\Models\V2\Survey\SurveyLegalEntity::inRandomOrder()->first()->id,
            'name' => $this->faker->word,
        ];
    }
}
