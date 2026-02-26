<?php

namespace App\CompanyList;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\BranchOffice;
use App\Report\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
