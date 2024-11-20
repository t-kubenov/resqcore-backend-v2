<?php

namespace Database\Factories;

use Carbon\Carbon;
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
        $callDate = $this->faker->dateTimeBetween('-5 years', '-5 months');
        $workStartDate = Carbon::parse($callDate)->addMinutes(rand(1, 60 * 24 * 3));

        $minor_injury_count = $this->faker->numberBetween(0, 10);
        $severe_injury_count = $this->faker->numberBetween(0, 10);
        $fatal_injury_count = $this->faker->numberBetween(0, 10);
        $evacuated_people_count = $this->faker->numberBetween(0, 10);
        $provided_medical_count = $this->faker->numberBetween(0, 10);
        $client_equipment_count = $this->faker->numberBetween(0, 10);
        $third_parties_count = $this->faker->numberBetween(0, 10);


        return [
            'branch_office_id' => BranchOffice::inRandomOrder()->first()->id,
            'facility_id' => Facility::factory(), // Create a related Facility
            'facility_owner_id' => Company::inRandomOrder()->first()->id,
            'reported_by' => User::where('role_id', 3)->inRandomOrder()->first()->id,
            'report_code' => $this->faker->unique()->bothify('##-##'),
            'caller' => $this->faker->name,

            'is_emergency_line' => $this->faker->boolean,
            'line_facility_owner' => $this->faker->name,
            'emergency_location' => $this->faker->address,
            'emergency_info' => $this->faker->sentence,

            'call_date' => $callDate,
            'work_start_date' => $workStartDate,
            'emergency_type' => EmergencyType::inRandomOrder()->first()->id,
            'minor_injury_count' => $minor_injury_count,
            'severe_injury_count' => $severe_injury_count,
            'fatal_injury_count' => $fatal_injury_count,
            'evacuated_people_count' => $evacuated_people_count,
            'provided_medical_count' => $provided_medical_count,
            'client_equipment_count' => $client_equipment_count,
            'third_parties_count' => $third_parties_count,

            'minor_injury_total' => $minor_injury_count,
            'severe_injury_total' => $severe_injury_count,
            'fatal_injury_total' => $fatal_injury_count,
            'evacuated_people_total' => $evacuated_people_count,
            'provided_medical_total' => $provided_medical_count,
            'client_equipment_total' => $client_equipment_count,
            'third_parties_total' => $third_parties_count,

             'is_closed' => false,
            // 'close_date' => $this->faker->optional()->dateTime,
            // 'branch_worker_count' => $this->faker->numberBetween(0, 50),
            // 'worker_man_hours' => $this->faker->randomFloat(2, 0, 1000),
            'work_scope' => $this->faker->paragraph,
            'situation_during_filing' => $this->faker->paragraph,
            'other_info' => $this->faker->paragraph,
        ];
    }

    public function isClosed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_closed' => true,
            'close_date' => Carbon::parse($attributes['work_start_date'])->addMinutes(rand(1, 60 * 24 * 3)),
            'branch_worker_count' => $this->faker->numberBetween(0, 15),
            'worker_man_hours' => $this->faker->randomFloat(2, 1, 50),
        ]);
    }
}
