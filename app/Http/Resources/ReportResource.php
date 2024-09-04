<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    protected $transport;

    // public function __construct($resource, $transport = null)
    // {
    //     parent::__construct($resource);
    //     $this->transport = $transport;
    // }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'report',
            'attributes' => $this->getAttributes(),
            'relationships' => $this->getRelationships(),
        ];
    }

    protected function getRelationships(): array
    {
        return [
            'updates' => UpdateResource::collection($this->whenLoaded('updates')),
            'transport' => TransportResource::collection($this->whenLoaded('transport')),
        ];
    }
}
