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
        // Allow formats: "YYYY-MM-DD|YYYY-MM-DD", "|YYYY-MM-DD", "YYYY-MM-DD|"
        if (!preg_match('/^(?:\d{4}-\d{2}-\d{2})?\|(?!.*\|)(?:\d{4}-\d{2}-\d{2})?$/', $value)) {
            $fail('The date range format is invalid. Use YYYY-MM-DD|YYYY-MM-DD, or leave one side empty for open-ended.');
            return;
        }

        [$startDate, $endDate] = explode('|', $value);

        $start = $startDate ? \DateTime::createFromFormat('Y-m-d', $startDate) : null;
        $end = $endDate ? \DateTime::createFromFormat('Y-m-d', $endDate) : null;

        $startValid = !$startDate || ($start && $start->format('Y-m-d') === $startDate);
        $endValid = !$endDate || ($end && $end->format('Y-m-d') === $endDate);

        if (!$startValid || !$endValid) {
            $fail('One or both dates are invalid.');
            return;
        }

        // If both dates are present, ensure logical order
        if ($start && $end && $start > $end) {
            $fail('The start date must be before or equal to the end date.');
        }
    }
}
