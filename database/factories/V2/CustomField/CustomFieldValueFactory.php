<?php

namespace Database\Factories\V2\CustomField;

use App\Enums\CustomFieldModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V2\CustomField\CustomFieldValue>
 */
class CustomFieldValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'custom_field_id' => 1,
            'model_type' => $this->faker->randomElement(CustomFieldModel::values()),
            'model_id' => 1,
        ];
    }

    public function string(): self
    {
        return $this->state(fn () => [
            'value_string' => $this->faker->word,
        ]);
    }

    public function text(): self
    {
        return $this->state(fn () => [
            'value_text' => $this->faker->sentence,
        ]);
    }

    public function int(): self
    {
        return $this->state(fn () => [
            'value_int' => $this->faker->numberBetween(1, 100),
        ]);
    }

    public function float(): self
    {
        return $this->state(fn () => [
            'value_float' => $this->faker->randomFloat(2, 1, 100),
        ]);
    }

    public function boolean(): self
    {
        return $this->state(fn () => [
            'value_boolean' => $this->faker->boolean,
        ]);
    }

    public function datetime(): self
    {
        return $this->state(fn () => [
            'value_datetime' => $this->faker->dateTime,
        ]);
    }
}
