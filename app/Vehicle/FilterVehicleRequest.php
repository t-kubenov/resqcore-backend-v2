<?php

namespace App\Vehicle;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
use Illuminate\Foundation\Http\FormRequest;

class FilterVehicleRequest extends FormRequest
{
    public function rules(): array
    {
        return ReportFilterFieldValidationDTO::fromArray(Vehicle::filters(), 'filtering');
    }
}
