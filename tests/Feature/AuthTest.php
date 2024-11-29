<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->branch_office_employee()->create();

    $this->response = test()->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $this->token = $this->response->json('data.token');

});

afterEach(function () {
    $this->user->tokens()->delete();
    $this->user->delete();
});

test('valid email, valid password login with token in result', function () {
    $this->response->assertStatus(200)->assertJsonStructure(['data' => ['token']]);
});

test('valid email, invalid password login', function () {

    $response = $this->postJson('/api/login', [
        'email' => $this->user->email,
        'password' => 'invalid-password',
    ]);

    $response->assertStatus(422);
});

test('invalid email, valid password login', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'hottester@email.xyz',
        'password' => 'password',
    ]);

    $response->assertStatus(422);
});

test('invalid email, invalid password login', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'hottertester@email.zyx',
        'password' => 'invalid-password',
    ]);

    $response->assertStatus(422);
});

test('user logout', function () {
    $response = $this
        ->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/logout');

    $response->assertStatus(200);
});


