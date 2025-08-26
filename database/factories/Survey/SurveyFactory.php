<?php

namespace Database\Factories\Survey;

use App\Models\BranchOffice;
use App\Models\User;
use App\Models\Survey\SurveyLegalEntity;
use App\Models\Survey\SurveyLegalEntityUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_office_id' => BranchOffice::where('is_central_office', false)->inRandomOrder()->first()->id,
            'created_by' => User::where('role_id', 3)->inRandomOrder()->first()->id,
            'legal_entity_id' => SurveyLegalEntity::query()->inRandomOrder()->first()->id,
            'legal_entity_unit_id' => SurveyLegalEntityUnit::query()->inRandomOrder()->first()->id,
            'survey_code' => $this->faker->unique()->numerify('##-##-##'),
            'survey_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'hazardous_facility_name' => $this->faker->company(),
        ];
    }
}
