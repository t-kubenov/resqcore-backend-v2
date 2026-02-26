<?php

namespace App\Document\DTOs;

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
        $required = !empty($validationField['required']) ? ['required'] : ['nullable'];
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

            $dto = match ($context) {
                'filtering' => self::forFiltering($key, $field),
                'creation' => self::forCreation($key, $field),
                'updating' => self::forUpdating($key, $field, $model),
                default => throw new \InvalidArgumentException('Unknown context: ' . $context),
            };

            // If subfields exist, process them recursively
            if (!empty($field['subfields'])) {
                $subRules = self::fromArray($field['subfields'], $context);

                // Merge: treat subfields as nested validation (dot notation)
                $dtoRules = [$dto->field => $dto->rules];
                foreach ($subRules as $subKey => $rules) {
                    $dtoRules[$subKey] = $rules;
                }

                return $dtoRules;
            }

            return [$dto->field => $dto->rules];
        })
            ->collapse() // flatten nested arrays
            ->toArray();
    }

    public function toArray(): array
    {
        return [
            $this->field => $this->rules
        ];
    }
}
