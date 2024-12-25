<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->branch_office_employee()->create();
});

afterEach(function () {
    $this->user->delete();
});

test('can change own password', function () {
    $this->actingAs($this->user)
        ->postJson('/api/change-password', [
            'old_password' => 'password',
            'new_password' => 'n3w-p4sswoRd!',
            'new_password_confirmation' => 'n3w-p4sswoRd!',
        ])
        ->assertStatus(200);
});


test('password difficulty', function () {
    $this->actingAs($this->user)
        ->postJson('/api/change-password', [
            'old_password' => 'password',
            'new_password' => 'password',
            'new_password_confirmation' => 'password',
        ])
        ->assertStatus(422);
});


test('wrong old password', function () {
    $this->actingAs($this->user)
        ->postJson('/api/change-password', [
            'old_password' => 'wrong-password',
            'new_password' => 'n3w-p4sswoRd!',
            'new_password_confirmation' => 'n3w-p4sswoRd!',
        ])
        ->assertStatus(422);
});


test('passwords do not match', function () {
    $this->actingAs($this->user)
        ->postJson('/api/change-password', [
            'old_password' => 'password',
            'new_password' => 'n3w-p4sswoRd!',
            'new_password_confirmation' => 'n3w-p4sswoRd!!',
        ])
        ->assertStatus(422);
});


test('upload avatar', function () {
    $this->actingAs($this->user)
        ->postJson('/api/upload-avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ])
        ->assertStatus(200);

    $this->assertNotNull($this->user->avatar);

    Storage::disk('public')->delete($this->user->avatar);
});


test('upload avatar failure', function () {
    $this->actingAs($this->user)
        ->postJson('/api/upload-avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.bmp'),
        ])
        ->assertStatus(422);

    $this->assertNull($this->user->avatar);
});


test('view images', function () {
    $this->actingAs($this->user)
        ->postJson('/api/upload-avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

    $this->assertNotNull($this->user->avatar);

    $this->actingAs($this->user)
        ->get('/api/image?path=' . $this->user->avatar)
        ->assertStatus(200);
});
