<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Models\V2\Report\Report;
use App\Traits\AltersDisplayedDates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
        'company_id',
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

    public function rescue_company(): HasOneThrough
    {
        return $this->hasOneThrough(RescueCompany::class, BranchOffice::class, localKey: 'branch_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getRescueCompanyIdAttribute(): int
    {
        return $this->branch_office->rescue_company_id;
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isAdmin(): bool
    {
        return $this->role->name === RoleEnum::Admin->value;
    }

    public function isCentralOffice(): bool
    {
        return $this->role->name === RoleEnum::CentralOfficeUser->value;
    }

    public function isBranchOffice(): bool
    {
        return $this->role->name === RoleEnum::BranchOfficeUser->value;
    }

    public function scopeWithFullName(Builder $query): Builder
    {
        return $query->select([
            'id',
            \DB::raw("CONCAT(last_name, ' ', first_name, ' ', patronymic) as name")
        ]);
    }
}
