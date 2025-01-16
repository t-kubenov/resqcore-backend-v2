<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\TransportType;
use App\Models\Update;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $update = Update::inRandomOrder()->first();

        return [
            'report_id' => $report->id,
            'update_id' => $update->id,
            'type' => TransportType::inRandomOrder()->first()->id,
            'count' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function isReportTransport(): static
    {
        return $this->state(fn (array $attributes) => [
            'update_id' => null,
        ]);
    }

    public function isUpdateTransport(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_id' => null,
        ]);
    }
}
