<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransportType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'rescue_company_id',
        'key',
        'name',
        'alias'
    ];
}
