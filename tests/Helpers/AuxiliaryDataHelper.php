<?php

namespace Tests\Helpers;

use App\Models\User;


function loadUsers (){
    return [
        'branch_user' => User::where('role_id', 3)->inRandomOrder()->first(),
        'central_user' => User::where('role_id', 2)->inRandomOrder()->first(),
    ];
};


function assertEndpoint ($object, $route, $data): void
{
    foreach ($object->users as $user) {
        $object->actingAs($user)
            ->get('/api/' . $route)
            ->assertStatus(200)
            ->assertJsonStructure($data);
    }
}
