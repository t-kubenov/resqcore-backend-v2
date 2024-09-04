<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;

class BranchOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
