<?php

namespace Database\Factories\Report\Transport;

use App\Report\Report;
use App\Report\Transport\Transport;
use App\Report\Transport\TransportType;
use App\Report\Update\Update;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transport>
 */
class TransportFactory extends Factory
{
    protected $model = Transport::class;

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
