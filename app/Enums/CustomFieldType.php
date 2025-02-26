<?php

namespace App\Enums;

enum CustomFieldType: string
{
    case Int = 'int';
    case Float = 'float';
    case String = 'string';
    case Text = 'text';
    case Datetime = 'datetime';
    case Boolean = 'boolean';
    case SingleChoiceEnum = 'single_choice_enum';
    case MultipleChoiceEnum = 'multiple_choice_enum';
    case MultiValue = 'multi_value';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function plainTypes(): array
    {
        return [
            self::Int,
            self::Float,
            self::String,
            self::Text,
            self::Datetime,
            self::Boolean,
        ];
    }

    public static function nonPlainTypes(): array
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
}


