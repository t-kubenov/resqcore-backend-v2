<?php

namespace App\Report;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'rescue_company_id',
        'key',
        'name',
        'alias'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'emergency_type');
    }
}
