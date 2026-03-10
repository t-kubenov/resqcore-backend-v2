<?php

namespace App\Report\Http;

use App\Report\BaseReport\BaseReportStoreRequest;
use App\Report\Report;

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
