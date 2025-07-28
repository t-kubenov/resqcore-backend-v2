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
        $condition = ['sometimes'];
        $type = [$field['type']->filteringValidationRule()];
        $rules = [];

        return new self(
            $key,
            array_merge(
                $condition,
                $type,
                $rules
            ));
    }

    public static function forCreation(string $key, array $field): self
    {
        $condition = !empty($field['required']) ? ['required'] : [];
        $type = [$field['type']->creationValidationRule()];
        $rules = $field['rules'] ?? [];

        return new self(
            $field['key'],
            array_merge(
                $condition,
                $type,
                $rules
            ));
    }

    public static function fromArray(array $fields, string $context)
    {
        return collect($fields)->map(function ($field, $key) use ($context) {

            return match ($context) {
                'filtering' => self::forFiltering($key, $field),
                'creation' => self::forCreation($key, $field),
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
