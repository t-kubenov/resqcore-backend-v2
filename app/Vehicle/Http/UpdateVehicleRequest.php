<?php

namespace App\Vehicle\Http;

use App\Document\DTOs\ReportFilterFieldValidationDTO;
use App\Vehicle\Vehicle;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function rules():array
    {
        return $this->getRules(new Vehicle());
    }

    protected function getRules(Vehicle $class): array
    {
        return ReportFilterFieldValidationDTO::fromArray($class->fieldDefinitions(), 'updating', $this->route('vehicle'));
    }
}
