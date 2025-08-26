<?php

namespace App\Http\Resources\Update;

use App\Http\Resources\TransportCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $log = parent::toArray($request);

        $log['transport'] = new TransportCollection($this['transport']);

        return $log;
    }
}
