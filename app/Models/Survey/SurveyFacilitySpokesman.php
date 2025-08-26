<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyFacilitySpokesman extends Model
{
    use HasFactory;

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
