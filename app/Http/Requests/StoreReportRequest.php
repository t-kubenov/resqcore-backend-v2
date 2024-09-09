<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            // 'data.attributes.branch_office_id'                  => ['required', 'integer'],
            // 'data.attributes.reported_by'                       => ['required'],
            'data.attributes.caller'                            => ['required'],
            'data.attributes.call_date'                         => ['required', 'date'],
            'data.attributes.work_start_date'                   => ['required', 'date'],
            'data.attributes.emergency_type'                    => ['required', 'array'],
            'data.attributes.work_scope'                        => ['required'],

            'data.attributes.is_emergency_line'                 => ['required', 'boolean'],
            'data.attributes.line_facility_owner'               => ['required_if:data.attributes.is_emergency_line,true', 'string'],
            'data.attributes.facility_owner_id'                 => ['required_if:data.attributes.is_emergency_line,false', 'integer', 'min:0'],

            'data.attributes.emergency_location'                => ['required', 'string'],
            'data.attributes.emergency_info'                    => ['nullable', 'string'],

            'data.attributes.minor_injury_count'                => ['required', 'integer', 'min:0'],
            'data.attributes.severe_injury_count'               => ['required', 'integer', 'min:0'],
            'data.attributes.fatal_injury_count'                => ['required', 'integer', 'min:0'],
            'data.attributes.evacuated_people_count'            => ['required', 'integer', 'min:0'],
            'data.attributes.provided_medical_count'            => ['required', 'integer', 'min:0'],
            'data.attributes.client_equipment_count'            => ['required', 'integer', 'min:0'],
            'data.attributes.third_parties_count'               => ['required', 'integer', 'min:0'],
            'data.attributes.branch_worker_count'               => ['nullable', 'integer', 'min:0'],
            'data.attributes.worker_man_hours'                  => ['nullable', 'numeric', 'min:0'],
            'data.attributes.situation_during_filing'           => ['nullable'],
            'data.attributes.other_info'                        => ['nullable'],
            'data.attributes.close_date'                        => ['nullable', 'date'],
            'data.attributes.transport'                         => ['nullable'],
            // 'data.attributes.transport.*.id'                    => ['required', 'integer', 'min:0', 'exists:transports,id'],
            // 'data.attributes.transport.*.count'                 => ['required', 'integer', 'min:0'],
        ];
    }

        /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'line_facility_owner.required_if' => 'The line facility location is required when the emergency line is true.',
            'facility_owner_id.required_unless' => 'The facility owner ID is required when the emergency line is false.',
        ];
    }
}
