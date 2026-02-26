<?php

namespace Database\Factories\CustomField;

use App\CustomField\CustomField;
use App\CustomField\CustomFieldOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomFieldOption>
 */
class CustomFieldOptionFactory extends Factory
{
    protected $model = CustomFieldOption::class;

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
