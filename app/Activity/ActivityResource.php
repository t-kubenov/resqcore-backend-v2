<?php

namespace App\Activity;

use App\Vehicle\Enums\ActionType;
use App\Vehicle\Enums\DriveType;
use App\Vehicle\Enums\FuelType;
use App\Vehicle\Enums\GearboxType;
use App\Vehicle\Enums\TechnicalCondition;
use App\Vehicle\Enums\UsageType;
use App\Tenant\BranchOffice;
use App\Vehicle\VehicleCompany;
use App\Vehicle\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource // de-facto VehicleActivityResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'by' => $this->causer->full_name?? null,
            'when' => $this->created_at->diffForHumans(),
            'description' => $this->composeDescription(),
            ...($this->event == 'updated'? ['changes' => $this->composeChanges()]: [])
        ];
    }

    private function composeDescription(): string
    {
        return __('activities.event.' . $this->event, ['document' => __('documents.types.' . $this->subject_type)]);
    }

    private function composeChanges()
    {
        $changes = [];

        foreach ($this->update_description as $change)
        {
            $changes[] = __('activities.event.update_change', [
                'field' => __('validation.attributes.data.attributes.' . $change['field']),
                'old' => $this->localizeFieldValue($change['field'], $change['old']),
                'new' => $this->localizeFieldValue($change['field'], $change['new']),
            ]);
        }

        return $changes;
    }

    private function localizeFieldValue(string $field, mixed $value): string
    {
        if (!$value) $value = __('validation.null');

        return match(true){
            $this->isEnumField($field) => $this->localizeEnum($field, $value),
            $this->isForeignKey($field) => $this->getForeignKeyName($field, $value),
            default => $value,
        };
    }

    private function isForeignKey($field): bool
    {
        return in_array($field, [
            'branch_office_id',
            'vehicle_type_id',
            'vehicle_owner_id',
            'vehicle_user_id',
        ]);
    }

    private function getForeignKeyName($field, $value): ?string
    {
        $relationships = [
            'branch_office_id' => BranchOffice::class,
            'vehicle_type_id' => VehicleType::class,
            'vehicle_owner_id' => VehicleCompany::class,
            'vehicle_user_id' => VehicleCompany::class,
            ];

        return $relationships[$field]::find($value)->name;
    }

    private function isEnumField($field): bool
    {
        return in_array($field, [
            'fuel_type',
            'gearbox',
            'drive',
            'usage_type',
            'technical_condition',
        ]);
    }

    private function localizeEnum($field, $value): string
    {
        $enums = [
            'fuel_type' => FuelType::class,
            'gearbox' => GearboxType::class,
            'drive' => DriveType::class,
            'usage_type' => UsageType::class,
            'action' => ActionType::class,
            'technical_condition' => TechnicalCondition::class,
        ];

        return __('etc.enum_options.'. $enums[$field]::tryFrom((int)$value)->name);
    }

}
