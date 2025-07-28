<?php

namespace App\Http\Requests\V2\Report;

use App\Models\V2\Report\Report;

class StoreReportRequest extends BaseReportStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->getRules(new Report());
    }
}
