<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->type,
            'name' => $this->name,
            'count' => $this->count,
        ];
    }
}
