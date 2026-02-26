<?php

namespace App\Activity;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    public function getUpdateDescriptionAttribute(): array
    {
        if ($this->event != 'updated')
        {
            return [];
        }

        return collect($this->properties['attributes'] ?? [])
            ->map(fn ($new, $key) => [
                'field' => $key,
                'old' => $this->properties['old'][$key] ?? null,
                'new' => $new,
            ])
            ->values()
            ->all();
    }
}
