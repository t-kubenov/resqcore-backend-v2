<?php

namespace Tests\Helpers;

use App\Models\User;


function getRandomCentralAndBranchUsers(): array
{
    $branchUser = User::where('role_id', 3)->inRandomOrder()->first();
    $centralUser = User::where('role_id', 2)->inRandomOrder()->first();

    if (!$branchUser || !$centralUser) {
        throw new \RuntimeException('Missing central or branch users in database.');
    }

    return [
        'branch_user' => $branchUser,
        'central_user' => $centralUser,
    ];
}


function loadAdmin ()
{
    return User::where('role_id', 1)->inRandomOrder()->first();
};

function assertUsersCanAccessEndpoint ($test_instance, $route, $data): void
{
    foreach ($test_instance->users as $user) {
        $test_instance->actingAs($user)
            ->get($route)
            ->assertStatus(200)
            ->assertJsonStructure($data);
    }
}
