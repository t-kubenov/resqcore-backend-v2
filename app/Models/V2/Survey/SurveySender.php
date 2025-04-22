<?php

namespace App\Models\V2\Survey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveySender extends Model
{
    protected $fillable = [
        'survey_id', //integer
        'name',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}
