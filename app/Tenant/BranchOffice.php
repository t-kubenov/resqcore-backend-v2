<?php

namespace App\Tenant;

use App\CompanyList\Facility;
use Illuminate\Database\Eloquent\Model;
use App\Report\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'rescue_company_id',
        'key',
        'name',
        'alias',
        'is_central_office',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function rescue_company()
    {
        return $this->belongsTo(RescueCompany::class);
    }

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
        return $this->hasMany(User::class, 'branch_id');
    }

    public function getCentralOffices()
    {
        return $this->where('is_central_office', true)->get();
    }

    public function getBranchOffices()
    {
        return $this->where('is_central_office', false)->get();
    }
}
