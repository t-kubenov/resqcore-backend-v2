<?php

namespace App\Http\Resources\Activity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'by' => $this->causer->full_name,
            'when' => $this->created_at->diffForHumans(),
            'description' => $this->composeDescription(),
        ];
    }

    private function composeDescription(): string
    {
        if ($this->event != 'updated')
        {
            return __('activities.event.' . $this->event, ['document' => __('documents.types.' . $this->subject_type)]);
        }

        $changes = __('activities.event.updated_head');

        foreach ($this->update_description as $change)
        {
            $changes .= __('activities.event.updated_body', [
                'old' => $change['old'],
                'new' => $change['new'],
                'field' => __('validation.attributes.data.attributes.' . $change['field']),
            ]);
        }

        return $changes;
    }
}
