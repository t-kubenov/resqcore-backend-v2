<?php

namespace App\Models\V2\Survey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyFacilitySpokesman extends Model
{
    protected $table = 'survey_facility_spokesman';

    protected $fillable = [
        'survey_id', //integer
        'name',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }
}
