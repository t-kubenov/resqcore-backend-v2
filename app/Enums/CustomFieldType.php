<?php

namespace App\Enums;

use App\Rules\DateRangeRule;
use App\Traits\EnumToArray;
use Attribute;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final class PlainType {}

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final class ComplexType {}

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final class EnumType {}


enum CustomFieldType: string
{
    use EnumToArray;

    #[PlainType]
    case Int = 'int';

    #[PlainType]
    case Float = 'float';

    #[PlainType]
    case String = 'string';

    #[PlainType]
    case Text = 'text';

    #[PlainType]
    case Date = 'date';

    #[PlainType]
    case Datetime = 'datetime';

    #[PlainType]
    case Boolean = 'boolean';

    #[PlainType, ComplexType, EnumType]
    case SingleChoiceEnum = 'single_choice_enum';

    #[ComplexType, EnumType]
    case MultipleChoiceEnum = 'multiple_choice_enum';

    #[ComplexType]
    case MultiValue = 'multi_value';

    #[ComplexType]
    case StringArray = 'string_array';

    #[ComplexType]
    case DoubleInput = 'double_input';

    public function creationValidationRule(): string
    {
        return match ($this) {
            self::Int, self::SingleChoiceEnum => 'integer',
            self::Float => 'numeric',
            self::String, self::Text => 'string',
            self::Date => 'date_format:Y-m-d',
            self::Datetime => 'date',
            self::Boolean => 'boolean',
            self::MultipleChoiceEnum, self::MultiValue, self::StringArray, self::DoubleInput => 'array',
        };
    }

    public function filteringValidationRule(): string|ValidationRule
    {
        return match ($this) {
            self::Int,  => 'integer',
            self::Float => 'numeric',
            self::String, self::Text, self::StringArray  => 'string',
            self::Date, self::Datetime => new DateRangeRule(),
            self::Boolean => 'boolean',
            self::SingleChoiceEnum, self::MultipleChoiceEnum, self::MultiValue, self::DoubleInput => 'array',
        };
    }

    private function hasAttribute(string $attribute): bool
    {
        $reflection = new \ReflectionEnumUnitCase(self::class, $this->name);
        return !empty($reflection->getAttributes($attribute));
    }

    public function isPlain(): bool
    {
        return $this->hasAttribute(PlainType::class);
    }

    public function isComplexType(): bool
    {
        return $this->hasAttribute(ComplexType::class);
    }

    public function isEnum(): bool
    {
        return $this->hasAttribute(EnumType::class);
    }

    public static function plainTypes(): array
    {
        return array_filter(self::cases(), fn (self $case) => $case->isPlain());
    }
}


