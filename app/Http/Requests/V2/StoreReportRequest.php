<?php

namespace App\Http\Requests\V2;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $attributes = $this->input('data.attributes', []);

        if (isset($attributes['branch_worker_count']) && $attributes['branch_worker_count'] == 0) {
            unset($attributes['branch_worker_count']);
        }

        if (isset($attributes['worker_man_hours']) && $attributes['worker_man_hours'] == 0) {
            unset($attributes['worker_man_hours']);
        }

        $this->merge([
            'data' => [
                'attributes' => array_merge($attributes, [
                    'minor_injury_count' => $attributes['minor_injury_count'] ?? 0,
                    'severe_injury_count' => $attributes['severe_injury_count'] ?? 0,
                    'fatal_injury_count' => $attributes['fatal_injury_count'] ?? 0,
                    'evacuated_people_count' => $attributes['evacuated_people_count'] ?? 0,
                    'provided_medical_count' => $attributes['provided_medical_count'] ?? 0,
                    'client_equipment_count' => $attributes['client_equipment_count'] ?? 0,
                    'third_parties_count' => $attributes['third_parties_count'] ?? 0,
                    'is_closed' => false,
                ]),
            ],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return collect(Report::fieldDefinitions())
            ->mapWithKeys(fn($field) => [$field['key'] => $field['rules']])
            ->toArray();
    }


    /**
     * Get custom attribute names for validation errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        // Provide user-friendly field names for validation errors
        return collect(Report::fieldDefinitions())
            ->mapWithKeys(fn($field) => [$field['key'] => $field['rules']])
            ->toArray();
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'line_facility_owner.required_if' => 'The line facility location is required when the emergency line is true.',
            'facility_owner_id.required_if' => 'The facility owner ID is required when the emergency line is false.',
        ];
    }

    public function beforeOrEqualToday($attribute, $value, $fail)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value);

        if ($date->gt(Carbon::today())) {
            $fail($attribute.' must be a date before or equal to today.');
        }
    }
}
