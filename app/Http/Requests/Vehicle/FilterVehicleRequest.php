<?php

namespace App\Http\Requests\Vehicle;

use App\DTOs\ReportFilterFieldValidationDTO;
use App\Models\Vehicle\Vehicle;
use Illuminate\Foundation\Http\FormRequest;

class FilterVehicleRequest extends FormRequest
{
    public function rules(): array
    {
        return ReportFilterFieldValidationDTO::fromArray(Vehicle::filters(), 'filtering');
    }
}
