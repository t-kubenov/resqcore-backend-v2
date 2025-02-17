<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    protected $fillable = [
        'rescue_company_id',
        'key',
        'name',
        'alias',
        'is_central_office',
    ];

    protected $hidden = ['created_at', 'updated_at'];

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
}
