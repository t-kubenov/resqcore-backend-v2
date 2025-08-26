<?php

namespace App\Http\Requests\V2\Report;

use App\DTOs\ReportFilterFieldValidationDTO;
use App\Models\V2\Report\ReportBase;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseReportStoreRequest extends FormRequest
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

        if (isset($attributes['worker_man_hours']) && $attributes['worker_man_hours'] == 0) {
            unset($attributes['worker_man_hours']);
        }

        if (isset($attributes['transport']) && $attributes['transport'] == []) {
            unset($attributes['transport']);
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
                    'branch_worker_count' => $attributes['branch_worker_count'] ?? 0,
                ]),
            ],
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    abstract public function rules(): array;

    protected function getRules(ReportBase $operation): array
    {
        return ReportFilterFieldValidationDTO::fromArray($operation::fieldDefinitions(), 'creation');
    }
}
