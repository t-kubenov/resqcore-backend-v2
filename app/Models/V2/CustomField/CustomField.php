<?php

namespace App\Models\V2\CustomField;

use App\Enums\CustomFieldType;
use App\Models\RescueCompany;
use Database\Factories\V2\CustomField\CustomFieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    /** @use HasFactory<CustomFieldFactory> */
    use HasFactory;

    protected $fillable = [
        'rescue_company_id',
        'parent_field',
        'model',
        'name',
        'label',
        'required',
        'validation',
        'type',
        'parameters', // array of CustomFieldParameters
    ];

    protected $casts = [
        'type' => CustomFieldType::class,
        'parameters' => 'array',
    ];

    public function options()
    {
        return $this->hasMany(CustomFieldOption::class);
    }

    public function rescue_company()
    {
        return $this->belongsTo(RescueCompany::class);
    }

    public function values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function parent_field()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function getOptionIdsAttribute(): array
    {
        return $this->options->pluck('id')->toArray();
    }
}
