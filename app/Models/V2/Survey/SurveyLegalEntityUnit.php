<?php

namespace App\Models\V2\Survey;

use App\Models\BranchOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Znck\Eloquent\Relations\BelongsToThrough;

class SurveyLegalEntityUnit extends Model
{
    use HasFactory;

    protected $table = 'legal_entity_units';

    protected $fillable = [
        'name', //string
        'legal_entity_id', //integer
    ];

    public function legal_entity(): BelongsTo
    {
        return $this->belongsTo(SurveyLegalEntity::class, 'legal_entity_id');
    }

    public function branch_office(): BelongsToThrough
    {
        return $this->belongsToThrough(BranchOffice::class, SurveyLegalEntity::class, 'legal_entity_id', 'id', 'id', 'branch_office_id');
    }
}
