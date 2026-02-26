<?php

namespace Database\Factories\Survey\Models;

use App\Survey\Models\SurveyLegalEntity;
use App\Tenant\BranchOffice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyLegalEntity>
 */
class SurveyLegalEntityFactory extends Factory
{
    protected $model = SurveyLegalEntity::class;

    public function definition(): array
    {
        return [
            'branch_office_id' => BranchOffice::inRandomOrder()->first()->id,
            'name' => $this->faker->company(),
        ];
    }
}
