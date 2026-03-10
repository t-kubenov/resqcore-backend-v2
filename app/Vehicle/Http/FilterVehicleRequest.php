<?php

namespace App\Vehicle\Http;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
use App\Vehicle\Vehicle;
use Illuminate\Foundation\Http\FormRequest;

class FilterVehicleRequest extends FormRequest
{
    public function rules(): array
    {
        return ReportFilterFieldValidationDTO::fromArray(Vehicle::filters(), 'filtering');
    }
}
