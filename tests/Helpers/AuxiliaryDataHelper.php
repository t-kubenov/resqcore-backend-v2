<?php

namespace Tests\Helpers;

use App\Models\User;


function loadUsers (){
    return [
        'branch_user' => User::where('role_id', 3)->inRandomOrder()->first(),
        'central_user' => User::where('role_id', 2)->inRandomOrder()->first(),
    ];
};

function loadAdmin (){
    return User::where('role_id', 1)->inRandomOrder()->first();
};

function assertEndpoint ($object, $route, $data): void
{
    foreach ($object->users as $user) {
        $object->actingAs($user)
            ->get('/api/v2/' . $route)
            ->assertStatus(200)
            ->assertJsonStructure($data);
    }
}
