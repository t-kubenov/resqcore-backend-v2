<?php

namespace Database\Factories\Survey;

use App\Models\BranchOffice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SurveyLegalEntityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_office_id' => BranchOffice::inRandomOrder()->first()->id,
            'name' => $this->faker->company(),
        ];
    }
}
