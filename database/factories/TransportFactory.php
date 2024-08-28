<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Operation;
use App\Models\Update;
// use App\Models\Transport;

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
        $operation = Operation::inRandomOrder()->first();
        $Update = $operation->updates()->inRandomOrder()->first();

        return [
            'operation_id' =>  $operation->id,
            'operation_update_id' =>  $Update->id?? null,
            'type' => $this->faker->numberBetween(1, 8),
            'count' => $this->faker->numberBetween(1, 10),
        ];
    }
}
