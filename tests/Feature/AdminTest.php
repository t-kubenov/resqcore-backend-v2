<?php

use App\Models\User;
use function Tests\Helpers\{loadAdmin};

beforeEach(function () {
    $this->admin = loadAdmin();
});

test('test_admin_can_create_users', function(){

    $response = $this->actingAs($this->admin)
        ->postJson(route('register'), [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'password' => 'passworD!2',
            'password_confirmation' => 'passworD!2',
            'role_id' => 3,
            'branch_id' => 1,
            'position' => 'Manager',
        ])
        ->assertStatus(201);

    $this->assertDatabaseHas('users', [
        'email' => $response->json('data.attributes.email'),
    ]);

    User::find($response->json('data.id'))->tokens()->delete();
    User::find($response->json('data.id'))->delete();
});
