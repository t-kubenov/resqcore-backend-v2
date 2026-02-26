<?php

namespace App\Survey\Models;

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

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function legal_entity(): BelongsTo
    {
        return $this->belongsTo(SurveyLegalEntity::class, 'legal_entity_id');
    }

    public function branch_office(): BelongsTo
    {
        return $this->legal_entity->branch_office();
    }
}
