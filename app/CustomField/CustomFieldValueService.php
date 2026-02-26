<?php

namespace App\CustomField;

use App\CustomField\Enums\FieldType;
use App\Report\BaseReport\ReportBase;
use Carbon\Exceptions\InvalidTypeException;

class CustomFieldValueService
{
    /**
     * @throws InvalidTypeException
     */
    public static function store(int $rescueCompanyId, ReportBase $baseReport, array $validatedData): void
    {
        $customFields = CustomField::where(
            [
                ['rescue_company_id', $rescueCompanyId],
                ['model', $baseReport::class],
            ])
            ->get();

        foreach ($customFields as $field) {
            if (!isset($validatedData[$field->name]) || $field->type === FieldType::MultiValue->value) {
                continue;
            }
            if($field->type === FieldType::MultipleChoiceEnum){
                self::handleMultipleChoiceEnumValue($validatedData[$field->name], $field, $baseReport, $rescueCompanyId);
                continue;
            }
            self::createCustomFieldValue($rescueCompanyId, $field, $baseReport, $validatedData[$field->name]);
        }
    }


    private static function createCustomFieldValue(int $rescueCompanyId, CustomField $field, ReportBase $baseReport, mixed $value): void
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

    private static function handleMultipleChoiceEnumValue(array $values, CustomField $field, ReportBase $baseReport, int $rescueCompanyId): void
    {
        foreach ($values as $value) {
            self::createCustomFieldValue($rescueCompanyId, $field, $baseReport, $value);
        }
    }
}
