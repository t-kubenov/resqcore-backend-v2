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
        return [
            'data.attributes.branch_office_id'                  => ['sometimes', 'integer', 'exists:branch_offices,id'],

            'data.attributes.caller'                            => ['required'],
            'data.attributes.call_date'                         => [
                'required',
                'date',
                'before_or_equal:data.attributes.work_start_date',
                'before_or_equal:tomorrow' //should be before or equal to today, but it is not working
            ],
            'data.attributes.work_start_date'                   => [
                'required',
                'date',
                'after_or_equal:data.attributes.call_date',
                'before_or_equal:tomorrow'
            ],
            'data.attributes.emergency_type.id'                 => ['required', 'integer', 'exists:emergency_types,id'],
            'data.attributes.work_scope'                        => ['required'],

            'data.attributes.is_emergency_line'                 => ['required', 'boolean'],

            'data.attributes.line_facility_owner'               => ['required', 'string'],
//            'data.attributes.line_facility_owner'               => ['required_if:data.attributes.is_emergency_line,true', 'string'],
//            'data.attributes.facility_owner_id'                 => ['required_if:data.attributes.is_emergency_line,false', 'integer', 'exists:companies,id'],

            'data.attributes.emergency_location'                => ['required', 'string'],
            'data.attributes.emergency_info'                    => ['nullable', 'string'],

            'data.attributes.minor_injury_count'                => ['required', 'integer', 'min:0'],
            'data.attributes.severe_injury_count'               => ['required', 'integer', 'min:0'],
            'data.attributes.fatal_injury_count'                => ['required', 'integer', 'min:0'],
            'data.attributes.evacuated_people_count'            => ['required', 'integer', 'min:0'],
            'data.attributes.provided_medical_count'            => ['required', 'integer', 'min:0'],
            'data.attributes.client_equipment_count'            => ['required', 'integer', 'min:0'],
            'data.attributes.third_parties_count'               => ['required', 'integer', 'min:0'],
            'data.attributes.branch_worker_count'               => [
                'nullable',
                'required_with:data.attributes.worker_man_hours,data.attributes.close_date',
                'integer',
                'min:0'
            ],
            'data.attributes.worker_man_hours'                  => [
                'nullable',
                'required_with:data.attributes.branch_worker_count,data.attributes.close_date',
                'numeric',
                'min:0'
            ],
            'data.attributes.situation_during_filing'           => ['nullable'],
            'data.attributes.other_info'                        => ['nullable'],
            'data.attributes.close_date'                        => [
                'nullable',
                'required_with:data.attributes.branch_worker_count,data.attributes.worker_man_hours',
                'date',
                'after_or_equal:data.attributes.work_start_date',
                'before_or_equal:tomorrow'
            ],
            'data.attributes.transport'                         => ['required', 'array', 'min:1'],
            'data.attributes.transport.*.id'                    => ['required', 'integer', 'exists:transport_types,id'],
            'data.attributes.transport.*.count'                 => ['required', 'integer', 'min:1'],
        ];
    }
}
