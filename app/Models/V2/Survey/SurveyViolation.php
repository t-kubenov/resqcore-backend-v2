<?php

namespace App\Models\V2\Survey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyViolation extends Model
{
    protected $fillable = [
        'survey_id',
        'description', //text
        'clause', //text? string?
        'elimination_prescription_date', //date, no time
        'elimination_completion',  //boolean
        'elimination_completion_date', //date, no time
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

}
