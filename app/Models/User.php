<?php

namespace App\Models;

use App\Enums\AccessLevel;
use App\Traits\AltersDisplayedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Znck\Eloquent\Traits\BelongsToThrough;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, AltersDisplayedDates,
        BelongsToThrough;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'email',
        'password',
        'role_id',
        'branch_id',
        'position',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function rescue_company()
    {
        return $this->belongsToThrough(
            RescueCompany::class,
            BranchOffice::class,
            foreignKeyLookup: [BranchOffice::class => 'branch_id']
        );
    }

    public function getRescueCompanyIdAttribute(): int
    {
        return $this->branch_office->rescue_company_id;
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAccessLevelAttribute(): AccessLevel
    {
        if ($this->role->name === 'admin') {
            return AccessLevel::APP;
        }

        if ($this->branch_office?->is_central_office) {
            return AccessLevel::COMPANY;
        }

        return AccessLevel::BRANCH;
    }
}
