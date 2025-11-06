<?php

namespace App\Http\Requests\Vehicle;

use App\DTOs\ReportFilterFieldValidationDTO;
use App\Models\Vehicle\Vehicle;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function rules():array
    {
        return $this->getRules(new Vehicle());
    }

    protected function getRules(Vehicle $class): array
    {
        return ReportFilterFieldValidationDTO::fromArray($class->fieldDefinitions(), 'creation');
    }
}
