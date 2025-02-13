<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class TransportType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'rescue_company_id',
        'key',
        'name',
        'alias'
    ];
}
