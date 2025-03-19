<?php

namespace App\Models\V2\CustomField;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldOption extends Model
{
    /** @use HasFactory<\Database\Factories\V2\CustomField\CustomFieldOptionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'custom_field_id',
    ];

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }

}
