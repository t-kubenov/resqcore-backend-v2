<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationUpdate extends BaseOperation
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
    ];


    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }
}
