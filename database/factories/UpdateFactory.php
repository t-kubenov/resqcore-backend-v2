<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Update;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

// use App\Models\BranchOffice;
// use App\Models\Facility;
// use App\Models\User;

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
        $report = Report::where('is_closed','=', 'false')->inRandomOrder()->first();

        return [
            'report_id' => $report->id,
            'reported_by' => $report->reporter->id,
            'minor_injury_count' => $this->faker->numberBetween(0, 10),
            'severe_injury_count' => $this->faker->numberBetween(0, 10),
            'fatal_injury_count' => $this->faker->numberBetween(0, 10),
            'evacuated_people_count' => $this->faker->numberBetween(0, 10),
            'provided_medical_count' => $this->faker->numberBetween(0, 10),
            'work_scope' => $this->faker->paragraph,
            'situation_during_filing' => $this->faker->paragraph,
            'client_equipment_count' => $this->faker->numberBetween(0, 10),
            'third_parties_count' => $this->faker->numberBetween(0, 10),
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (Update $update) {
            $update->report->update([
                'minor_injury_total' => $update->report->minor_injury_total + $update->minor_injury_count,
                'severe_injury_total' => $update->report->severe_injury_total + $update->severe_injury_count,
                'fatal_injury_total' => $update->report->fatal_injury_total + $update->fatal_injury_count,
                'evacuated_people_total' => $update->report->evacuated_people_total + $update->evacuated_people_count,
                'provided_medical_total' => $update->report->provided_medical_total + $update->provided_medical_count,
                'client_equipment_total' => $update->report->client_equipment_total + $update->client_equipment_count,
                'third_parties_total' => $update->report->third_parties_total + $update->third_parties_count,
            ]);
        });
    }

    public function isClosingUpdate(): static
    {
        return $this->state(fn (array $attributes) => [
            'branch_worker_count' => $this->faker->numberBetween(1, 15),
            'worker_man_hours' => $this->faker->randomFloat(2, 1, 40),
            'close_date' => Carbon::parse(Report::find($attributes['report_id'])->work_start_date)->addMinutes(rand(1, 60 * 24 * 3)),
        ])->afterCreating(function (Update $update) {
            $update->report->update([
                'is_closed' => true,
                'branch_worker_count' => $update->branch_worker_count,
                'worker_man_hours' => $update->worker_man_hours,
                'close_date' => $update->close_date,
            ]);
        });
    }
}
