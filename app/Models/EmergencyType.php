<?php

namespace App\Models;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
