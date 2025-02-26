<?php

namespace Database\Factories;

use App\Enums\CustomFieldType;
use App\Models\V2\CustomField\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V2\CustomField\CustomField>
 */
class CustomFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rescue_company_id' => 4,
            'parent_field' => null,
            'name' => $this->faker->word,
            'label' => $this->faker->word,
            'model' => 'Report',
            'required' => $this->faker->boolean(20),
            'validation' => $this->faker->word,
            'type' => $this->faker->randomElement(CustomFieldType::values()),
        ];
    }

    public function subfield(): self
    {
        return $this->state([
            'parent_field' => CustomField::where('type', 'multi_value')->inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(CustomFieldType::plainTypes()),
        ]);
    }
}
