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
    public abstract function getReadableData(): array; //todo: avoid displaying fields not present in columns

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

    public static function columnsByPredicate(callable $predicate, array $fields = null): array
    {
        $fields ??= static::fieldDefinitions();

        return array_values(array_map(
            fn($field) => [
                'key' => $field['name'],
                'name' => $field['label'],
            ],
            array_filter($fields, $predicate)
        ));
    }

    public static function columnsByParameter(?FieldParameter $parameter, array $fields = null): array
    {
        return static::columnsByPredicate(
            fn ($column) => in_array($parameter, $column['parameters'], true),
            $fields
        );
    }

    public static function columnsByKeys(array $keys): array
    {
        $keys = array_flip($keys);

        return static::columnsByPredicate(fn ($column) => isset($keys[$column['name']]));
    }

    public static function orderColumns(array $order): array
    {
        $columns = static::columnsByKeys($order);
        $order = array_flip($order);

        return collect($columns)
            ->sortBy(fn($columns) => $order[$columns['key']])
            ->values()
            ->toArray();
    }

    public static function tableColumns(): array
    {
        return static::columnsByPredicate(
            fn($field) => in_array(FieldParameter::ShowInTable, $field['parameters'])
        );
    }

    public static function exportColumns(): array
    {
        return static::columnsByPredicate(
            fn($field) => in_array(FieldParameter::ShowInExport, $field['parameters'])
        );
    }

    public static function cardColumns(): array
    {
        return static::columnsByPredicate(
            fn($field) => !in_array(FieldParameter::HideFromCard, $field['parameters'])
        );
    }
}
