<?php

namespace App\Models\V2\CustomField;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldOption extends Model
{
    /** @use HasFactory<\Database\Factories\CustomFieldOptionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'order',
    ];

    public function field()
    {
        return $this->belongsTo(CustomField::class);
    }

}
