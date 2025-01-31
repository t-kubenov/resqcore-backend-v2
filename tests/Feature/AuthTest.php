<?php

use App\Models\User;


beforeEach(function () {
    $this->user = User::whereIn('role_id', [2, 3])->inRandomOrder()->first();
    $this->token = $this->user->createToken('test-token')->plainTextToken;

    $this->login_route = route('login');
    $this->register_route = route('register');
    $this->logout_route = route('logout');
});


test('valid email, valid password login with token in result', function () {
    $user = User::factory()->branch_office_employee()->create();

    $this->postJson($this->login_route, [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'token',
            ],
        ]);

    $this->user->tokens()->delete();
    $user->delete();
});


test('valid email, invalid password login', function () {
    $this->postJson($this->login_route, [
        'email' => $this->user->email,
        'password' => 'invalid-password',
    ])
        ->assertStatus(422);
});


test('invalid email, valid password login', function () {
    $this->postJson($this->login_route, [
        'email' => 'hottester@email.xyz',
        'password' => 'password',
    ])
        ->assertStatus(422);
});


test('invalid email, invalid password login', function () {
    $this->postJson($this->login_route, [
        'email' => 'hottertester@email.zyx',
        'password' => 'invalid-password',
    ])
        ->assertStatus(422);
});


test('user logout', function () {
    $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson($this->logout_route)
        ->assertStatus(200);
});


test('user logout without token', function () {
    $this->postJson($this->logout_route)->assertStatus(401);
});


test('user logout with invalid token', function () {
    $this->withHeader('Authorization', 'Bearer invalid-token')
        ->postJson($this->logout_route)
        ->assertStatus(401);
});


test('register as a normal user', function () {
    $this->actingAs($this->user)
        ->postJson($this->register_route, [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@sigma.it',
            'password' => 'passworD!2',
            'password_confirmation' => 'passworD!2',
            'role_id' => 3,
            'branch_id' => 1,
            'position' => 'Manager',
        ])
        ->assertStatus(403);
});

