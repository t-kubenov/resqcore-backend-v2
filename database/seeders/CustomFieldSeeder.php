<?php

namespace Database\Seeders;

use App\Enums\CustomFieldType;
use App\Models\RescueCompany;
use App\Models\CustomField\CustomField;
use App\Models\CustomField\CustomFieldOption;
use App\Models\CustomField\CustomFieldValue;
use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rescueCompany = RescueCompany::where('key', 'test')->first();

        foreach (CustomFieldType::cases() as $type) {

            $field = CustomField::factory()
                ->create([
                    'rescue_company_id' => $rescueCompany->id,
                    'type' => $type->value,
                ]);

            if ($type === CustomFieldType::SingleChoiceEnum || $type === CustomFieldType::MultipleChoiceEnum) {
                $this->createOptions($field->id);
                $this->createValues(CustomFieldType::Int, $field->id, array_rand($field->option_ids));
            }

            elseif ($type === CustomFieldType::MultiValue) {
                $this->createSubfield($rescueCompany->id, $field->id);
            }

            else {
                $this->createValues($type, $field->id);
            }
        }
    }

    private function createSubfield(int $rescueCompanyId, int $customFieldId): void
    {
        CustomField::factory()
            ->subfield()
            ->create([
                'rescue_company_id' => $rescueCompanyId,
                'parent_field' => $customFieldId,
            ]);

        $this->createValues(CustomFieldType::plainTypes()[array_rand(CustomFieldType::plainTypes())], $customFieldId);
    }

    private function createOptions(int $customFieldId): void
    {
        CustomFieldOption::factory()
            ->count(5)
            ->create([
                'custom_field_id' => $customFieldId,
            ]);
    }

    private function createValues(CustomFieldType $type, int $customFieldId, $value = null): void
    {
        $type = $type->value;

        CustomFieldValue::factory()
            ->{$type}()->create([
                'custom_field_id' => $customFieldId,
                ...($value? ['value_'.$type => $value] : []),
            ]);
    }

}
