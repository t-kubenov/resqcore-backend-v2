<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\BranchOffice;
use App\Models\Facility;
use App\Models\User;
use App\Models\EmergencyType;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
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
            'facility_id' => Facility::factory(), // Create a related Facility
            'facility_owner_id' => Company::inRandomOrder()->first()->id,
            'reported_by' => User::where('role_id', 3)->inRandomOrder()->first()->id,
            'report_code' => $this->faker->unique()->bothify('##-##'),
            'caller' => $this->faker->name,
            // 'line_facility_location' => $this->faker->address,    // This is not used in the model
            'line_facility_owner' => $this->faker->name,
            'emergency_location' => $this->faker->address,
            'emergency_info' => $this->faker->sentence,
            'call_date' => $this->faker->dateTime,
            'work_start_date' => $this->faker->dateTime,
            'emergency_type' => EmergencyType::inRandomOrder()->first()->id,
            'minor_injury_count' => $this->faker->numberBetween(0, 10),
            'severe_injury_count' => $this->faker->numberBetween(0, 10),
            'fatal_injury_count' => $this->faker->numberBetween(0, 10),
            'evacuated_people_count' => $this->faker->numberBetween(0, 100),
            'provided_medical_count' => $this->faker->numberBetween(0, 100),
            'client_equipment_count' => $this->faker->numberBetween(0, 50),
            'third_parties_count' => $this->faker->numberBetween(0, 50),
            // 'branch_worker_count' => $this->faker->numberBetween(0, 50),
            // 'worker_man_hours' => $this->faker->randomFloat(2, 0, 1000),
            'work_scope' => $this->faker->paragraph,
            'situation_during_filing' => $this->faker->paragraph,
            'other_info' => $this->faker->paragraph,
            'is_emergency_line' => $this->faker->boolean,
            'close_date' => $this->faker->optional()->dateTime,
            // 'is_closed' => $this->faker->boolean,
        ];
    }

    // create emergency line operation

    public function isEmergencyLineOperation(): static
    {
        return $this->state(fn (array $attributes) => [
            'facility_id' => null,
            'emergency_location' => $this->faker->address,
            'line_facility_owner' => $this->faker->name,
            'is_emergency_line' => true,
        ]);
    }

    public function isContractOperation(): static
    {
        return $this->state(fn (array $attributes) => [
            'facility_id' => Facility::factory(),
            'emergency_location' => $this->faker->address,
            'line_facility_owner' => null,
            'is_emergency_line' => false,
        ]);
    }

    public function isClosed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_closed' => true,
            'close_date' => $this->faker->dateTime,
            'branch_worker_count' => $this->faker->numberBetween(0, 50),
            'worker_man_hours' => $this->faker->randomFloat(2, 0, 1000),
        ]);
    }

    public function isNotClosed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_closed' => false,
            'close_date' => null,
        ]);
    }
}
