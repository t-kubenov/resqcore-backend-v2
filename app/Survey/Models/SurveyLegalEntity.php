<?php

namespace App\Survey\Models;

use App\Tenant\BranchOffice;
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

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class);
    }
}
