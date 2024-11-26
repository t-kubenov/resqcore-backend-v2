<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->attributesToArray();

        if ($this->relationLoaded('transport')) {
            $attributes['transport'] = TransportResource::collection($this->transport);
        }

        return [
            'id' => $this->id,
            'type' => 'update',
            'attributes' => $attributes
        ];
    }
}
