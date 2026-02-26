<?php

namespace Tests\Helpers;

use App\Tenant\BranchOffice;
use App\Tenant\User;
use App\Report\Report;
use Carbon\Carbon;


function generateReport(User $user): array
{
    $report = Report::factory()->make()->toArray();
    $report['call_date'] = Carbon::parse($report['call_date'])->format('Y-m-d H:i:s');
    $report['work_start_date'] = Carbon::parse($report['work_start_date'])->format('Y-m-d H:i:s');
    $report['emergency_type'] = generateEmergencyType($user);

    unset($report['reported_by']);
    unset($report['report_code']);

    return array_merge($report, [
        'transport' => [
            [
                'id' => generateTransportType($user),
                'count' => fake()->numberBetween(1, 10),
            ]
        ]
    ]);
}


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
}


function assertUsersCanAccessEndpoint ($test_instance, $route, $data): void
{
    foreach ($test_instance->users as $user) {
        $test_instance->actingAs($user)
            ->get($route)
            ->assertStatus(200)
            ->assertJsonStructure($data);
    }
}


function getRandomBranchIdFromCentralOffice(int $central_office_id): int
{
    $central_office = BranchOffice::find($central_office_id);

    return BranchOffice::where('rescue_company_id', $central_office['rescue_company_id'])
        ->where('is_central_office', false)
        ->inRandomOrder()->first()->id;
}
