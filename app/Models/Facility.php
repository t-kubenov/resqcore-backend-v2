<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

//use Illuminate\Database\Eloquent\Model;


class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner',
        'location',
        'overseeing_branch',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'owner');
    }

    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'overseeing_branch');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
