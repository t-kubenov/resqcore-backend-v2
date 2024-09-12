<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $token;

    public function __construct($resource, $token = null)
    {
        parent::__construct($resource);
        $this->token = $token;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = parent::toArray($request);

        $data = [
            'id' => $this->id,
            'type' => 'user',
            'attributes' => $attributes,
        ];

        if ($this->token) {
            $data['token'] = $this->token;
        }

        return $data;
    }
}
