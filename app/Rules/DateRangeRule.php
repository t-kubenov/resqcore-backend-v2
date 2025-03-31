<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DateRangeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Ensure format is YYYY-MM-DD|YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}\|\d{4}-\d{2}-\d{2}$/', $value)) {
            $fail('The date range format is invalid. Use YYYY-MM-DD|YYYY-MM-DD.');
            return;
        }

        // Split the date range
        [$startDate, $endDate] = explode('|', $value);

        // Check if both dates are valid
        if (!strtotime($startDate) || !strtotime($endDate)) {
            $fail('Invalid date format.');
            return;
        }

        // Ensure the start date is not greater than the end date
        if ($startDate > $endDate) {
            $fail('The start date must be before the end date.');
        }
    }
}
