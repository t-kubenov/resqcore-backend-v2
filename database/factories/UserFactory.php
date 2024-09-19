<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\BranchOffice;
use App\Models\Company;
use App\Models\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $is_company = fake()->boolean();

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            // 'company' => $is_company? Company::factory(): null,
            // 'branch' => $is_company? null: BranchOffice::inRandomOrder()->first()->id,
            // 'role_id' => Role::random()->id,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // create admin user
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_name' => 'John',
            'last_name' => 'ResQ',
            'patronymic' => 'Adminovich',
            'email' => 'admin@resq.kz',
            'password' => Hash::make('123'),
            'role_id' => 1,
        ])
        ->afterCreating(function (User $user) {
            $user->assignRole('admin');
        });
    }

    // create company user
    public function company_employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => Company::factory(),
            'branch_id' => null,
            'role_id' => 4,
        ])
        ->afterCreating(function (User $user) {
            $user->assignRole('company_user');
        });
    }

    // branch office employee

    public function branch_office_employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => null,
            'branch_id' => BranchOffice::inRandomOrder()->first()->id,
            'role_id' => 3,
        ])
        ->afterCreating(function (User $user) {
            $user->assignRole('branch_office_user');
        });
    }

    // central office user

    public function central_office_user(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => null,
            'branch_id' => null,
            'role_id' => 2,
        ])
        ->afterCreating(function (User $user) {
            $user->assignRole('central_office_user');
        });
    }
}
