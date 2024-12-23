<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use withFaker;

    protected User $admin;
    protected string $token;



    /**
     * @param int $age
     * @param string $name
     */
//    protected function setUp(): void
    public function setUp(): void
    {
        parent::setUp();

        $response = $this->postJson('/api/login', [
            'email' => 'admin@resq.kz',
            'password' => config('app.etc.admin_password'),
        ]);

        $this->token = $response->json('data.token');

        $this->test_user = null;
    }

    protected function tearDown(): void
    {
        $this->postJson('/api/logout', [], [
            'Authorization', 'Bearer ' . $this->token,
        ]);

        if ($this->test_user) {
            $this->test_user->tokens()->delete();
            $this->test_user->delete();
        }

        parent::tearDown();
    }

    public function test_admin_can_create_users()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/register', [
                'first_name' => $this->faker->firstName(),
                'last_name' => $this->faker->lastName(),
                'email' => $this->faker->email(),
                'password' => 'passworD!2',
                'password_confirmation' => 'passworD!2',
                'role_id' => 3,
                'branch_id' => 1,
                'position' => 'Manager',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $response->json('data.attributes.email'),
        ]);


        User::find($response->json('data.id'))->tokens()->delete();
        User::find($response->json('data.id'));
    }
}
