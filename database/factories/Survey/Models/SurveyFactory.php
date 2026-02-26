<?php

namespace Database\Factories\Survey\Models;

use App\Survey\Models\Survey;
use App\Survey\Models\SurveyLegalEntity;
use App\Survey\Models\SurveyLegalEntityUnit;
use App\Tenant\BranchOffice;
use App\Tenant\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Survey>
 */
class SurveyFactory extends Factory
{
    protected $model = Survey::class;

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
