<?php

namespace App\Models\V2\Survey;

use App\Models\BranchOffice;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    protected $fillable = [
        'branch_office_id',
        'created_by', //integer
        'legal_entity_id', //integer
        'legal_entity_unit_id', //integer
        'survey_code', //string, branch office number-year number-survey
        'survey_date', // datetime
        'hazardous_facility_name', //string
    ];


    protected function surveyCode(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->branch_office->id . '-' . $this->survey_date->format('y') . '-' . $this->id
        );
        // this is how it'll look like: 1-23-1
    }

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class);
    }

    public function senders(): HasMany
    {
        return $this->hasMany(SurveySender::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(SurveyFacilitySpokesman::class);
    }

    public function violations(): HasMany
    {
        return $this->hasMany(SurveyViolation::class);
    }

    public function legal_entity(): BelongsTo
    {
        return $this->belongsTo(SurveyLegalEntity::class);
    }

    public function legal_entity_unit(): BelongsTo
    {
        return $this->belongsTo(SurveyLegalEntityUnit::class);
    }

}
