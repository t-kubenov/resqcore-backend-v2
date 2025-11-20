<?php

namespace Database\Factories;

use App\Models\BranchOffice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'branch_id' => BranchOffice::factory(),
            // 'role_id' => Role::random()->id,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function branch_office_employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => null,
            'branch_id' => BranchOffice::where('is_central_office', false)->inRandomOrder()->first()->id,
            'role_id' => 3,
        ])
            ->afterCreating(function (User $user) {
                $user->assignRole('branch_office_user');
            });
    }

    public function branch_office(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 3,
            'branch_id' => BranchOffice::factory(),
        ])
            ->afterCreating(function (User $user) {
            $user->assignRole('branch_office_user');
        });
    }

    public function central_office(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 2,
            'branch_id' => BranchOffice::factory()->central_office(),
        ])
            ->afterCreating(function (User $user) {
            $user->assignRole('central_office_user');
        });
    }

}
