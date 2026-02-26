<?php

namespace App\CompanyList;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
