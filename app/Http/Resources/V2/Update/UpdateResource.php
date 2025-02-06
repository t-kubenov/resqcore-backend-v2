<?php

namespace App\Http\Resources\V2\Update;

use App\Http\Resources\V2\TransportCollection;
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
        return [
            'id' => $this->id,
            'type' => 'update',
            'attributes' => $this->getAttributes(),
            'relationships' => $this->getRelationships(),
        ];
    }

    protected function getRelationships()
    {
        return [
            'transport' => new TransportCollection($this->transport??[]),
        ];
    }
}
