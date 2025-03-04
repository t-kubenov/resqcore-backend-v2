<?php

namespace App\Http\Services\V2;

use App\Enums\CustomFieldType;
use App\Models\BaseOperation;
use App\Models\V2\CustomField\CustomField;
use App\Models\V2\CustomField\CustomFieldValue;
use Carbon\Exceptions\InvalidTypeException;

class CustomFieldValueService
{
    /**
     * @throws InvalidTypeException
     */
    public static function store(int $rescueCompanyId, BaseOperation $baseReport, array $validatedData): void
    {
        $customFields = CustomField::where(
            [
                ['rescue_company_id', $rescueCompanyId],
                ['model', get_class_name($baseReport::class)],
            ])
            ->get();

        foreach ($customFields as $field) {
            if (!isset($validatedData[$field->name]) || $field->type === CustomFieldType::MultiValue->value) {
                continue;
            }
            if($field->type === CustomFieldType::MultipleChoiceEnum){
                self::handleMultipleChoiceEnumValue($validatedData[$field->name], $field, $baseReport, $rescueCompanyId);
                continue;
            }
            self::createCustomFieldValue($rescueCompanyId, $field, $baseReport, $validatedData[$field->name]);
        }
    }


    private static function createCustomFieldValue(int $rescueCompanyId, CustomField $field, BaseOperation $baseReport, mixed $value): void
    {
        $column = CustomFieldValue::getStorableColumn($field->type);

        CustomFieldValue::create([
            'company_id'      => $rescueCompanyId,
            'custom_field_id' => $field->id,
            'model_type'      => $baseReport::class,
            'model_id'        => $baseReport->id,
            $column           => $value
        ]);
    }

    private static function handleMultipleChoiceEnumValue(array $values, CustomField $field, BaseOperation $baseReport, int $rescueCompanyId): void
    {
        foreach ($values as $value) {
            self::createCustomFieldValue($rescueCompanyId, $field, $baseReport, $value);
        }
    }
}
