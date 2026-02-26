<?php

namespace App\Report;

use App\Report\BaseReport\BaseReportStoreRequest;

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
