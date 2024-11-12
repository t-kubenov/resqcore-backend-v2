<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyType extends Model
{
    public $timestamps = false;

    protected $fillable = ['key', 'name'];

    public function reports()
    {
        return $this->hasMany(Report::class, 'emergency_type');
    }
}
