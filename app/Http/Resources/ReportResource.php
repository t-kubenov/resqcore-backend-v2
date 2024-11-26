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
        $attributes = $this->attributesToArray();

        $transportResource = TransportResource::collection($this->whenLoaded('transport'));
        $updateResource = UpdateResource::collection($this->whenLoaded('updates'));

        if ($transportResource) {
            $attributes['transport'] = $transportResource;
        }

        return [
            'id' => $this->id,
            'type' => 'report',
            'attributes' => $attributes,
            'updates' => $updateResource,
        ];
    }
}
