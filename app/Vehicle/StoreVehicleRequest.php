<?php

namespace App\Vehicle;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
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
