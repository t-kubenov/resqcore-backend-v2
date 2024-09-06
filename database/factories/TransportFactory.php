<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Report;
use App\Models\Update;
use App\Models\TransportType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transport>
 */
class TransportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $report = Report::inRandomOrder()->first();
        $Update = $report->updates()->inRandomOrder()->first();

        return [
            'report_id' =>  $report->id,
            'update_id' =>  $Update->id?? null,
            'type' => TransportType::inRandomOrder()->first()->id,
            'count' => $this->faker->numberBetween(1, 10),
        ];
    }
}
