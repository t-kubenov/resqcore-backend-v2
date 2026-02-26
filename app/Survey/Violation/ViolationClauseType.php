<?php

namespace App\Survey\Violation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationClauseType extends Model
{
    use HasFactory;

    protected $table = 'survey_violation_clause_types';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'key',
        'alias',
        'link',
    ];

    public function violation(): BelongsTo
    {
        return $this->hasMany(Violation::class);
    }
}
