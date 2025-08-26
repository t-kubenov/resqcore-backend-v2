<?php

namespace Database\Factories\CustomField;

use App\Models\CustomField\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomField\CustomFieldOption>
 */
class CustomFieldOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'custom_field_id' => CustomField::where('type', ['single_choice_enum', 'multiple_choice_enum'])->first()->id,
            'name' => $this->faker->word,
            'label' => $this->faker->word,
            'order' => $this->faker->randomNumber(),
        ];
    }
}
