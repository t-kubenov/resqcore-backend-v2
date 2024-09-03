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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.branch_office_id' => ['required', 'integer'],
            'data.attributes.reported_by' => ['required'],
            // 'data.attributes.report_code' => ['required'],
            'data.attributes.caller' => ['required'],
            'data.attributes.call_date' => ['required', 'date'],
            'data.attributes.work_start_date' => ['required', 'date'],
            'data.attributes.emergency_type' => ['required', 'array'],
            'data.attributes.is_emergency_line' => ['required', 'boolean'],
            'data.attributes.work_scope' => ['required'],
            'data.attributes.emergency_location' => ['required'],
            'data.attributes.emergency_info' => ['nullable', 'string'],
            'data.attributes.facility_id' => ['nullable', 'integer'],
            'data.attributes.minor_injury_count' => ['nullable', 'integer'],
            'data.attributes.severe_injury_count' => ['nullable', 'integer'],
            'data.attributes.fatal_injury_count' => ['nullable', 'integer'],
            'data.attributes.evacuated_people_count' => ['nullable', 'integer'],
            'data.attributes.provided_medical_count' => ['nullable', 'integer'],
            'data.attributes.client_equipment_count' => ['nullable', 'integer'],
            'data.attributes.third_parties_count' => ['nullable', 'integer'],
            'data.attributes.branch_worker_count' => ['nullable', 'integer'],
            'data.attributes.worker_man_hours' => ['nullable', 'numeric'],
            'data.attributes.situation_during_filing' => ['nullable'],
            'data.attributes.other_info' => ['nullable'],
            'data.attributes.close_date' => ['nullable', 'date'],
            'data.attributes.is_closed' => ['required', 'boolean'],
            'data.attributes.transport' => ['nullable'],
        ];
    }
}
