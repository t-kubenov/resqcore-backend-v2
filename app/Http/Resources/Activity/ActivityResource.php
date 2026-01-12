<?php

namespace App\Http\Resources\Activity;

use App\Enums\Vehicle\ActionType;
use App\Enums\Vehicle\DriveType;
use App\Enums\Vehicle\FuelType;
use App\Enums\Vehicle\GearboxType;
use App\Enums\Vehicle\TechnicalCondition;
use App\Enums\Vehicle\UsageType;
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

//        dd($this->update_description);

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
            $this->isForeignKey($field) => $this->getForeignKeyName($value),
            default => $value,
        };
    }

    private function getForeignKeyName($field): string
    {
        return $this->$field->name;
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

    private function isForeignKey($field): bool
    {
        return in_array($field, [
            'branch_office_id',
            'vehicle_type_id',
            'vehicle_owner_id',
            'vehicle_user_id',
        ]);
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
}
