<?php

namespace App\Traits;

use App\Enums\CustomFieldParameter;


trait FormatsColumns
{
    use AltersDisplayedDates;

    /**
     * Get field definitions.
     *
     * @return array<int, array{
     *     key: string,
     *     name: string,
     *     type: string,
     *     label: string,
     *     alias: ?string,
     *     required: ?bool,
     *     rules: ?array<string, string>,
     *     options: ?string,
     *     parameters?: array<int, CustomFieldParameter>
     * }>
     */
    public static function fieldDefinitions(): array
    {
        return [];
    }

    public static function filters(): array
    {
        return array_filter(static::fieldDefinitions(), function ($field) {

            $needle = auth()->user()->isBranchOffice()?
                CustomFieldParameter::IsFilterable: CustomFieldParameter::IsFilterableForCA;

            return in_array($needle, $field['parameters'] ?? []);
        });
    }

    public static function columns(): array
    {
        return array_values(array_map(
            fn($field) => [
                'key' => $field['name'],
                'name' => $field['label'],
            ],
            array_filter(static::fieldDefinitions(), fn($field) =>
                in_array(CustomFieldParameter::ShowInTable, $field['parameters'])
            )
        ));
    }

    public static function exportColumns(): array
    {
        return array_values(array_map(
            fn($field) => [
                'key' => $field['name'],
                'name' => $field['label'],
            ],
            array_filter(static::fieldDefinitions(), fn($field) =>
                in_array(CustomFieldParameter::ShowInExport, $field['parameters'])
            )
        ));
    }

    public static function cardColumns(): array
    {
        return array_values(array_map(
            fn($field) => [
                'key' => $field['name'],
                'name' => $field['label'],
            ],
            array_filter(static::fieldDefinitions(), fn($field) =>
                in_array(CustomFieldParameter::ShowInCard, $field['parameters'])
            )
        ));
    }
}
