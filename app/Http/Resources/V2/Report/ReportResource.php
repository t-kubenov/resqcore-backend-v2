<?php

namespace App\Http\Resources\V2\Report;

use App\Http\Resources\V2\GenericRelationshipResource;
use App\Http\Resources\V2\TransportCollection;
use App\Http\Resources\V2\TransportResource;
use App\Http\Resources\V2\Update\UpdateCollection;
use App\Http\Resources\V2\UserResource;
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
                'attributes' => $this->getModifiedAttributes(),
                'relationships' => $this->getRelationships(),
                'update_track' => $this->getUpdateTrack(),
            ],
            'original' => $this->getAttributes($request),

        ];
    }

    public function getModifiedAttributes()
    {
        return [
            'branch_office' => $this->branch_office?->name,
            'reported_by' => $this->reporter?->full_name,
            'report_code' => $this->report_code,
            'caller' => $this->caller,
            'facility_owner' => $this->line_facility_owner,
            'emergency_location' => $this->emergency_location,
            'emergency_info' => $this->emergency_info,
            'call_date' => $this->call_date?->format(config('app.date_format')),
            'work_start_date' => $this->work_start_date?->format(config('app.date_format')),
            'emergency_name' => $this->emergency->name,
            'minor_injury_total' => $this->minor_injury_total,
            'severe_injury_total' => $this->severe_injury_total,
            'fatal_injury_total' => $this->fatal_injury_total,
            'evacuated_people_total' => $this->evacuated_people_total,
            'provided_medical_total' => $this->provided_medical_total,
            'client_equipment_total' => $this->client_equipment_total,
            'third_parties_total' => $this->third_parties_total,
            'other_info' => $this->other_info,
            'is_emergency_line' => $this->is_emergency_line? 'Да': 'По договору',
            'last_update_created_at' => $this->last_update_created_at?->format(config('app.date_format')),
            'branch_worker_count' => $this->branch_worker_count,
            'worker_man_hours' => $this->worker_man_hours,
            'close_date' => $this->close_date?->format(config('app.date_format')),
            'is_closed' => $this->is_closed,
        ];
    }

    public function getColumns(){
        $highlighted = [
            'report_code',
            'is_closed',
        ];
        $attributes = array_diff(
            array_keys($this->getModifiedAttributes()),
            $highlighted
        );

        return [
            'highlighted' => collect($highlighted)->map(fn($attribute) => [
                'key' => $attribute,
                'name' => __('reports.fields.' . $attribute . '.name'),
                'alias' => __('reports.fields.' . $attribute . '.alias'),
            ])->toArray(),
            'attributes' => collect($attributes)->map(fn($attribute) => [
                'key' => $attribute,
                'name' => __('reports.fields.' . $attribute . '.name'),
                'alias' => __('reports.fields.' . $attribute . '.alias'),
            ])->toArray(),
        ];
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
