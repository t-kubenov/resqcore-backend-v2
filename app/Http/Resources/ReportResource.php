<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    protected $transport;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'columns' => $this->getColumns(),
            'data' =>
            [
                'id' => $this->id,
                'type' => 'report',
                'attributes' => $this->getAttributes($request),
                'relationships' => $this->getRelationships(),
                'update_track' => $this->getUpdateTrack(),
            ],

        ];
    }

    public function getColumns(){
        $attributes = array_keys($this->getAttributes());

        return collect($attributes)->map(fn($attribute) => [
            'key' => $attribute,
            'name' => __('reports.fields.' . $attribute . '.name'),
            'alias' => __('reports.fields.' . $attribute . '.alias'),
        ])->toArray();
    }

    public function getRelationships()
    {
        return [
            'branch_office' => new GenericRelationshipResource($this->branch_office),
            'facility' => new GenericRelationshipResource($this->facility),
            'emergency_type' => new GenericRelationshipResource($this->emergency),
            'transport' => new TransportCollection($this->transport??[]),
            'reported_by' => new UserResource($this->reporter),
            'updates' => new UpdateCollection($this->updates?? []),
        ];
    }


    /**
     * Generate the update track for the report.
     *
     * @return array
     */
    protected function getUpdateTrack(): array
    {
        $fields = [
            'evacuated_people_count' => [],
            'provided_medical_count' => [],
            'work_scope' => [],
            'situation_during_filing' => [],
            'client_equipment_count' => [],
            'third_parties_count' => [],
        ];

        // Merge the report and its updates
        $items = collect([$this->resource])->merge($this->updates ?? []);

        // Process injuries
        $fields['injuries'] = $items->map(function ($item) {
            return [
                'time' => $item->created_at->format(config('app.date_format')),
                'minor_injury_count' => $item->minor_injury_count ?? 0,
                'severe_injury_count' => $item->severe_injury_count ?? 0,
                'fatal_injury_count' => $item->fatal_injury_count ?? 0,
            ];
        })->toArray();

        // Process transport
        $fields['transport'] = $items->map(function ($item) {
            return [
                'time' => $item->created_at->format(config('app.date_format')),
                'data' => TransportResource::collection($item->transport ?? []),
            ];
        })->toArray();

        // Process other fields
        $other_fields = [
            'evacuated_people_count',
            'provided_medical_count',
            'work_scope',
            'situation_during_filing',
            'client_equipment_count',
            'third_parties_count',
        ];

        foreach ($other_fields as $field) {
            $fields[$field] = $items->map(function ($item) use ($field) {
                return [
                    'time' => $item->created_at->format(config('app.date_format')),
                    'data' => $item->{$field} ?? ($field === 'work_scope' || $field === 'situation_during_filing' ? null : 0),
                ];
            })->toArray();
        }

        return $fields;
    }
}
