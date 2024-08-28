<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Operation;
use App\Models\Transport;


class Update extends BaseOperation
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
    ];


    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function transport()
    {
        return $this->hasMany(Transport::class);
    }
}
