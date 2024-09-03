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
        return [
            "id" => 1,
            "type" => "departure",
            "attributes" => [
                'report_code' => $this->report_code,
                'work_start_date' => $this->work_start_date,
                'close_date' => $this->close_date,
                'facility_owner' => $this->facility->owner_->name ?? $this->line_facility_owner,
                'emergency_id' => $this->emergency_type, // Derive from emergency type
                'emergency_location' => $this->emergency_location,
                'branch_worker_count' => $this->branch_worker_count,
                'worker_man_hours' => $this->worker_man_hours,
                'branch_office_id' => $this->branch_office_id,
            ]
        ];
    }
}
