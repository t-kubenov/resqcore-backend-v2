<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('transport');
        // $this->load('branch_office');
        // $this->load('emergency');

        $transportResource = TransportResource::collection($this->whenLoaded('transport'));

        $this->facility_owner?->makeHidden(['created_at', 'updated_at']);
        $this->branch_office?->makeHidden(['created_at', 'updated_at']);

        return [
            "id" => $this->id,
            "type" => "departure",
            "attributes" => [
                'report_code' => $this->report_code,
                'work_start_date' => $this->work_start_date,
                'close_date' => $this->close_date,
                'is_emergency_line' => $this->is_emergency_line,
                'facility_owner' => $this->facility_owner,
                'line_facility_owner' => $this->line_facility_owner,
                'emergency_type' => $this->emergency, // Derive from emergency type
                'emergency_location' => $this->emergency_location,
                'branch_worker_count' => $this->branch_worker_count,
                'worker_man_hours' => $this->worker_man_hours,
                'branch_office' => $this->branch_office,
                'transport' => $transportResource,
                'is_closed' => $this->is_closed,
            ]
        ];
    }
}
