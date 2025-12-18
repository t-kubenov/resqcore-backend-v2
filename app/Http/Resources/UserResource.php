<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
        return [
            'id' => $this->id,
            'type' => 'user',
            'attributes' => $this->getAttributes($request),
            'token' => $this->when($this->token, $this->token),
        ];
    }

    private function getAttributes(Request $request): array
    {
        return [
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'patronymic'            => $this->patronymic,
            'email'                 => $this->email,
            'role_id'               => $this->role?->name,
            'rescue_company_id'     => $this->rescue_company?->alias,
            'branch_id'             => $this->branch_office?->name,
            'position'              => $this->position,
            'avatar'                => $this->avatar? Storage::disk()->url($this->avatar): null,
            'permissions'           => $this->permissions->pluck('name')
        ];
    }
}
