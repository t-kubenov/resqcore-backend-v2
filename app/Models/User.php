<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Models\Report\Report;
use App\Traits\AltersDisplayedDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, AltersDisplayedDates;

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

    protected $hidden = [
        'company_id',
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (!$user->rescue_company_id && $user->branch_id) {
                $user->rescue_company_id = BranchOffice::find($user->branch_id)->rescue_company_id;
            }
        });
    }

    public function rescue_company(): BelongsTo
    {
        return $this->belongsTo(RescueCompany::class);
    }

    public function branch_office(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class, 'branch_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
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
}
