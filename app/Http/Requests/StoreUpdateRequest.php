<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
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
            'data.attributes.minor_injury_count'                => ['nullable', 'integer'],
            'data.attributes.severe_injury_count'               => ['nullable', 'integer'],
            'data.attributes.fatal_injury_count'                => ['nullable', 'integer'],
            'data.attributes.evacuated_people_count'            => ['nullable', 'integer'],
            'data.attributes.provided_medical_count'            => ['nullable', 'integer'],
            'data.attributes.client_equipment_count'            => ['nullable', 'integer'],
            'data.attributes.third_parties_count'               => ['nullable', 'integer'],
            'data.attributes.work_scope'                        => ['required'],
            'data.attributes.situation_during_filing'           => ['nullable'],
            'data.attributes.branch_worker_count'               => ['nullable', 'integer'],
            'data.attributes.worker_man_hours'                  => ['nullable', 'numeric'],
            'data.attributes.close_date'                        => ['nullable', 'date'],
            'data.attributes.transport'                         => ['nullable'],
        ];
    }
}
