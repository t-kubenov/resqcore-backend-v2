<?php

namespace App\DTOs;

class ReportFilterFieldValidationDTO
{
    public string $field;
    public array $rules;


    private function __construct(string $field, array $rules)
    {
        $this->field = $field;
        $this->rules = $rules;
    }


//string $field, CustomFieldType $type, bool $required = false, array $extraRules = []
    public static function forFiltering(string $key, array $field): self
    {
        $required = ['sometimes'];
        $typeValidation = [$field['type']->filteringValidationRule()];
        $rules = [];

        return new self(
            $key,
            array_merge(
                $required,
                $typeValidation,
                $rules
            ));
    }

    public static function forCreation(string $key, array $field): self
    {
        $required = !empty($field['required']) ? ['required'] : [];
        $typeValidation = [$field['type']->creationValidationRule()];
        $rules = $field['rules'] ?? [];

        return new self(
            $field['key'],
            array_merge(
                $required,
                $typeValidation,
                $rules
            ));
    }

    public static function forUpdating(string $key, array $validationField, $model): self
    {
        $required = !empty($validationField['required']) ? ['required'] : [];
        $typeValidation = [$validationField['type']->creationValidationRule()];
        $rules = $validationField['rules'] ?? [];

        $rules = array_map(function ($rule) use ($model) {
            if (is_string($rule) && str_starts_with($rule, 'unique:')) {
                $rule .= ',' . $model->id;
            }
            return $rule;
        }, $rules);

        return new self(
            $validationField['key'],
            array_merge(
                $required,
                $typeValidation,
                $rules
            )
        );
    }

    public static function fromArray(array $fields, string $context, $model = null)
    {
        return collect($fields)->map(function ($field, $key) use ($model, $context) {

            return match ($context) {
                'filtering' => self::forFiltering($key, $field),
                'creation' => self::forCreation($key, $field),
                'updating' => self::forUpdating($key, $field, $model),
                default => throw new \InvalidArgumentException('Unknown context: ' . $context),
            };
        })
            ->mapWithKeys(function (ReportFilterFieldValidationDTO $dto) {
                return [$dto->field => $dto->rules];
        })->toArray();
    }

    public function toArray(): array
    {
        return [
            $this->field => $this->rules
        ];
    }
}
