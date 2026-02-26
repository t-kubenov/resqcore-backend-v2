<?php

namespace App\Report\Update;

use App\Report\BaseReport\BaseReportStoreRequest;

class StoreUpdateRequest extends BaseReportStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->getRules(new Update());
    }
}
