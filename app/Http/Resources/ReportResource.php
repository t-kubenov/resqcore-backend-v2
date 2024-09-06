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
    public function toArray(Request $request): array
    {
        $attributes = $this->getAttributes();

        if ($this->relationLoaded('transport')) {
            $attributes['transport'] = TransportResource::collection($this->transport);
        }

        if ($this->relationLoaded('updates')) {
            $attributes['updates'] = UpdateResource::collection($this->updates);
        }

        return [
            'id' => $this->id,
            'type' => 'report',
            'attributes' => $attributes
        ];
    }
}
