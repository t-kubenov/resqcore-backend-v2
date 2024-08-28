<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

// use App\Models\BranchOffice;
// use App\Models\Facility;
// use App\Models\User;
use App\Models\Operation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Update>
 */
class UpdateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $operation = Operation::inRandomOrder()->first();

        return [
            'operation_id' => $operation->id,
            'minor_injury_count' => $this->faker->numberBetween(0, 10),
            'severe_injury_count' => $this->faker->numberBetween(0, 10),
            'fatal_injury_count' => $this->faker->numberBetween(0, 10),
            'evacuated_people_count' => $this->faker->numberBetween(0, 100),
            'provided_medical_count' => $this->faker->numberBetween(0, 100),
            'work_scope' => $this->faker->paragraph,
            'situation_during_filing' => $this->faker->paragraph,
            'client_equipment_count' => $this->faker->numberBetween(0, 50),
            'third_parties_count' => $this->faker->numberBetween(0, 50),
            // 'branch_worker_count' => $this->faker->numberBetween(0, 50),
            // 'worker_man_hours' => $this->faker->randomFloat(2, 0, 1000),
            // 'close_date' => $this->faker->dateTime,
        ];
    }

    public function isClosingUpdate(): static
    {
        return $this->state(fn (array $attributes) => [
            'branch_worker_count' => $this->faker->numberBetween(0, 50),
            'worker_man_hours' => $this->faker->randomFloat(2, 0, 1000),
            'close_date' => $this->faker->dateTime,
        ]);
    }




}
