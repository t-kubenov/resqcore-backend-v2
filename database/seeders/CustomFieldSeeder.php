<?php

namespace Database\Seeders;

use App\CustomField\CustomField;
use App\CustomField\CustomFieldOption;
use App\CustomField\CustomFieldValue;
use App\CustomField\Enums\FieldType;
use App\Tenant\RescueCompany;
use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rescueCompany = RescueCompany::where('key', 'test')->first();

        foreach (FieldType::cases() as $type) {

            $field = CustomField::factory()
                ->create([
                    'rescue_company_id' => $rescueCompany->id,
                    'type' => $type->value,
                ]);

            if ($type === FieldType::SingleChoiceEnum || $type === FieldType::MultipleChoiceEnum) {
                $this->createOptions($field->id);
                $this->createValues(FieldType::Int, $field->id, array_rand($field->option_ids));
            }

            elseif ($type === FieldType::MultiValue) {
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

        $this->createValues(FieldType::plainTypes()[array_rand(FieldType::plainTypes())], $customFieldId);
    }

    private function createOptions(int $customFieldId): void
    {
        CustomFieldOption::factory()
            ->count(5)
            ->create([
                'custom_field_id' => $customFieldId,
            ]);
    }

    private function createValues(FieldType $type, int $customFieldId, $value = null): void
    {
        $type = $type->value;

        CustomFieldValue::factory()
            ->{$type}()->create([
                'custom_field_id' => $customFieldId,
                ...($value? ['value_'.$type => $value] : []),
            ]);
    }

}
