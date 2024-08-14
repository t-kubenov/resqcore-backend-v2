<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class operation_transports extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'transport_id',
    ];
}
