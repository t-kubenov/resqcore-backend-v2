<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum CustomFieldType: string
{
    use EnumToArray;

    case Int = 'int';
    case Float = 'float';
    case String = 'string';
    case Text = 'text';
    case Datetime = 'datetime';
    case Boolean = 'boolean';
    case SingleChoiceEnum = 'single_choice_enum';
    case MultipleChoiceEnum = 'multiple_choice_enum';
    case MultiValue = 'multi_value';


    public static function plainTypes(): array
    {
        return [
            self::Int,
            self::Float,
            self::String,
            self::Text,
            self::Datetime,
            self::Boolean,
            self::SingleChoiceEnum,
        ];
    }

    public static function nonPlainTypes(): array
    {
        return [
            self::MultipleChoiceEnum,
            self::MultiValue,
        ];
    }

    public static function complexTypes(): array
    {
        return [
            self::SingleChoiceEnum,
            self::MultipleChoiceEnum,
            self::MultiValue,
        ];
    }

    public function isPlain(): bool
    {
        return in_array($this, self::plainTypes(), true);
    }

    public function isComplexType(): bool
    {
        return in_array($this, self::complexTypes(), true);
    }
}


