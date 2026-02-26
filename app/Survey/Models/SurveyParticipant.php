<?php

namespace App\Survey\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class SurveyParticipant extends Model
{
    use HasFactory;

    protected static string $type;

    protected $table = 'survey_participants';

    protected $fillable = [
        'survey_id', //integer
        'name',
        'position',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->type = static::$type;
        });

        static::addGlobalScope(static::$type, function ($builder) {
            $builder->where('type', static::$type);
        });
    }
}
