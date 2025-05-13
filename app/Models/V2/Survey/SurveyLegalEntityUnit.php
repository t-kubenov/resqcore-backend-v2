<?php

namespace App\Models\V2\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyLegalEntityUnit extends Model
{
    use HasFactory;

    protected $table = 'legal_entity_units';

    protected $fillable = [
        'name', //string
        'legal_entity_id', //integer
    ];

    public function legalEntity(): BelongsTo
    {
        return $this->belongsTo(SurveyLegalEntity::class, 'legal_entity_id');
    }
}
