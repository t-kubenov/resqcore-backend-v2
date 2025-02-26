<?php

namespace App\Http\Requests\V2\CustomField;

use App\Enums\CustomFieldType;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomFieldRequest extends FormRequest
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
//            'rescue_company_id' => 4,
//            'parent_field' => null,
//            'name' => $this->faker->word,
//            'label' => $this->faker->word,
//            'model' => 'Report',
//            'required' => $this->faker->boolean(20),
//            'validation' => $this->faker->word,
//            'type' => $this->faker->randomElement(CustomFieldType::values()),

        return [
            //
        ];
    }
}
