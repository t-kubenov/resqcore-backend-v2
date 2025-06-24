<?php

namespace App\Models\V2\Survey;

use App\Models\BranchOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyLegalEntity extends Model
{
    use HasFactory;

    protected $table = 'legal_entities';

    protected $fillable = [
        'name', //string
        'branch_office_id', //integer
    ];

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class);
    }
}
