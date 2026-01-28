<?php

namespace App\Models;

use App\Enums\FieldParameter;
use App\Traits\AltersDisplayedDates;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class Document extends Model
{
    use AltersDisplayedDates;

    protected $perPage = 20;

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
     *     parameters?: array<int, FieldParameter>
     * }>
     */
    protected static abstract function fieldDefinitions(): array;

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }

    public function rescue_company(): BelongsTo
    {
        return $this->branch_office->rescue_company();
    }

    public static function filters(): array
    {
        return array_filter(static::fieldDefinitions(), function ($field) {

            $needle = auth()->user()->isBranchOffice()?
                FieldParameter::IsFilterable: FieldParameter::IsFilterableForCA;

            return in_array($needle, $field['parameters'] ?? []);
        });
    }

    public static function tableColumns(): array
    {
        return array_values(array_map(
            fn($field) => [
                'key' => $field['name'],
                'name' => $field['label'],
            ],
            array_filter(static::fieldDefinitions(), fn($field) =>
            in_array(FieldParameter::ShowInTable, $field['parameters'])
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
            in_array(FieldParameter::ShowInExport, $field['parameters'])
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
            in_array(FieldParameter::ShowInCard, $field['parameters'])
            )
        ));
    }
}
