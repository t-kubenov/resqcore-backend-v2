<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'alias',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function reports()
    {
        return $this->hasManyThrough(Report::class, BranchOffice::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, BranchOffice::class);
    }

    public function branch_offices()
    {
        return $this->hasMany(BranchOffice::class);
    }

    public function emergency_types()
    {
        return $this->hasMany(EmergencyType::class);
    }

    public function transport_types()
    {
        return $this->hasMany(TransportType::class);
    }
}
