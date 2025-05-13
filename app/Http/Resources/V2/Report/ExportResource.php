<?php

namespace App\Http\Resources\V2\Report;

use App\Models\User;
use App\Models\V2\Sushi\DepartureType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('transport');
        $attributes = $this->attributesToArray();

        return array_merge([
            "id" => $this->id,
            "type" => "departure",
            "attributes" => [
                'report_code' => $this->report_code,
                'call_date' => $attributes['call_date'],
                'work_start_date' => $attributes['work_start_date'],
                'close_date' => $attributes['close_date'],
                'is_emergency_line' => DepartureType::getDepartureType(User::where('id', $this->reporter->id)->first(), $this->is_emergency_line),
                'line_facility_owner' => $this->line_facility_owner,
                'emergency_type' => $this->emergency?->name, // Derive from emergency type
                'emergency_location' => $this->emergency_location,
                'branch_worker_total' => $this->branch_worker_total,
                'worker_man_hours' => $this->worker_man_hours,
                'branch_office' => $this->branch_office?->name,
                'transport_totals' => $this->transport_totals,
                'is_closed' => __('reports.fields.is_closed.'. ($this->is_closed ? 'true' : 'false')),
            ]
        ], $this->customFieldValueArray());
    }
}
