<?php

namespace Database\Factories\CustomField;

use App\CustomField\CustomField;
use App\CustomField\Enums\FieldType;
use App\Tenant\RescueCompany;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends Factory<CustomField>
 */
class CustomFieldFactory extends Factory
{
    protected $model = CustomField::class;

    public function definition(): array
    {
        return [
            'rescue_company_id' => RescueCompany::inRandomOrder()->first()->id,
            'parent_field' => null,
            'name' => Str::random(16),
            'label' => $this->faker->word,
            'model' => 'App\Report\Report',
            'required' => $this->faker->boolean(20),
            'validation' => $this->faker->word,
            'type' => $this->faker->randomElement(FieldType::values()),
        ];
    }

    public function subfield(): self
    {
        return $this->state([
            'parent_field' => CustomField::where('type', 'multi_value')->inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(FieldType::plainTypes()),
        ]);
    }
}
