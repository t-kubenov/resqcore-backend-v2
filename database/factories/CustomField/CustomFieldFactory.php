<?php

namespace Database\Factories\CustomField;

use App\Enums\CustomFieldType;
use App\Models\RescueCompany;
use App\Models\CustomField\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomField\CustomField>
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
            'rescue_company_id' => RescueCompany::inRandomOrder()->first()->id,
            'parent_field' => null,
            'name' => Str::random(16),
            'label' => $this->faker->word,
            'model' => 'App\Models\Report\Report',
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
