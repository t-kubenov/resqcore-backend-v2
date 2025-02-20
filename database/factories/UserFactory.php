<?php

namespace Database\Factories;

use App\Models\BranchOffice;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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


        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            // 'company' => $is_company? Company::factory(): null,
//             'branch_id' =>  BranchOffice::inRandomOrder()->first()->id,
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

    // branch office employee
    public function branch_office_employee_by_id(int $branch_office_id): static
    {

        return $this->state(fn (array $attributes) => [
            'company_id' => null,
            'branch_id' => $branch_office_id,
            'role_id' => 3,
        ])
        ->afterCreating(function (User $user) {
            $user->assignRole('branch_office_user');
        });
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

    // central office user

    public function central_office_user_by_id(int $branch_office_id): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => null,
            'branch_id' => $branch_office_id,
            'role_id' => 2,
        ])
        ->afterCreating(function (User $user) {
            $user->assignRole('central_office_user');
        });
    }
}
