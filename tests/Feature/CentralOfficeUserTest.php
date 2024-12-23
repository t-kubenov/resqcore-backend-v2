<?php

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;


beforeEach(function () {
    $this->user = User::where('role_id', 2)->inRandomOrder()->first();
});


test('can see all departures', function () {
    $this->actingAs($this->user)
        ->get('/api/departures')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'report_code',
                        'work_start_date',
                        'close_date',
                        'is_emergency_line',
                        // etc.
                    ]
                ],
            ],
        ]);
});


test('can see all departures with filters', function () {

    $branch_offices = \App\Models\BranchOffice::inRandomOrder()
        ->limit(3)
        ->pluck('id')
        ->toArray();

    // get 3 of emergency type ids
    $emergency_type = \App\Models\EmergencyType::inRandomOrder()
        ->limit(3)
        ->pluck('id')
        ->toArray();

    $is_emergency_line = fake()->boolean();

    $date_start = fake()->dateTimeBetween('-10 years', '-5 years')->format('Y-m-d H:i:s');

    $date_end = fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d H:i:s');

    $response = $this->actingAs($this->user)
        ->get('/api/departures?'.http_build_query([
                'branch_office_id' => $branch_offices,
                'emergency_type' => $emergency_type,
                'is_emergency_line' => $is_emergency_line,
                'date_start' => $date_start,
                'date_end' => $date_end,
            ]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'report_code',
                        'work_start_date',
                        'close_date',
                        'is_emergency_line',
                        // etc.
                    ]
                ],
            ],
        ]);

    expect($response->json('data'))->each(function ($report)
    use ($branch_offices, $emergency_type, $is_emergency_line, $date_start, $date_end) {
        // Extract the raw value from the Pest expectation
        $attributes = $report->value['attributes'];

        // Ensure call_date is valid and parse it
        $call_date = Carbon::parse($attributes['call_date']);

        expect($attributes['emergency_type']['id'])->toBeIn($emergency_type)
            ->and($attributes['branch_office']['id'])->toBeIn($branch_offices)
            ->and($attributes['is_emergency_line'])->toBe($is_emergency_line)
            ->and($call_date->format('Y-m-d H:i:s'))
            ->toBeGreaterThanOrEqual($date_start)
            ->toBeLessThanOrEqual($date_end);
    });

});


test('can see reports by id', function () {
    $report = Report::inRandomOrder()->first();

    $this->actingAs($this->user)
        ->get('/api/reports/'.$report->id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'report_code',
                    'work_start_date',
                    'close_date',
                    'is_emergency_line',
                    // etc.
                ]
            ],
        ]);
});


test('can access count table endpoint', function () {
    $this->actingAs($this->user)
        ->get('/api/departures/count-table')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'cells' => [
                    '*' => [
                        'emergency_type',
                        'branch_office_id',
                        'count',
                    ],
                ],
                'total_per_type',
                'total_per_branch_office',
                'overall_total',
            ]
        ]);
});


test('cannot create reports', function () {
    $this->actingAs($this->user)
        ->post('/api/reports', [])
        ->assertStatus(403);
});


test('cannot create updates', function () {
    $report = Report::where('is_closed', false)->inRandomOrder()->first();

    $this->actingAs($this->user)
        ->post('/api/reports/update/'.$report->id, [])
        ->assertStatus(403);
});
