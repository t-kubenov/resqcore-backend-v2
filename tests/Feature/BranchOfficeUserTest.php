<?php

use App\Models\Report;
use App\Models\Transport;
use App\Models\Update;
use App\Models\User;
use Carbon\Carbon;


beforeEach(function () {
    $this->user = User::where('role_id', 3)->inRandomOrder()->first();
});

// todo: verify the accuracy of the data

test('can view all reports of own branch', function () {
    $this->actingAs($this->user)
        ->get(route('reports.index'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'report_code',
                        'caller',
                        // etc.
                    ]
                ],
            ],
        ])
        ->assertJsonFragment([
            'total' => Report::where('branch_office_id', $this->user->branch_id)->count(),
        ]);
});


test('can view all reports with filters', function () {

    // get 3 of emergency type ids
    $emergency_type = \App\Models\EmergencyType::inRandomOrder()
        ->limit(3)
        ->pluck('id')
        ->toArray();

    $is_emergency_line = fake()->boolean();

    $date_start = fake()->dateTimeBetween('-10 years', '-5 years')->format('Y-m-d H:i:s');

    $date_end = fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d H:i:s');

    $this->actingAs($this->user)
        ->get(route('reports.index').'?'.http_build_query([
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
                        'caller',
                        // etc.
                    ]
                ],
            ],
        ])
        ->assertJsonFragment([
            'total' => Report::where('branch_office_id', $this->user->branch_id)
                ->whereIn('emergency_type', $emergency_type)
                ->where('is_emergency_line', $is_emergency_line)
                ->whereBetween('call_date', [$date_start, $date_end])
                ->count(),
        ]);
});


test('can view single report of own branch', function () {
    $report = Report::where('branch_office_id', $this->user->branch_id)->inRandomOrder()->first();

    $this->actingAs($this->user)
        ->get(route('reports.show', $report->id))
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'branch_office',
                    'caller',
                    // etc.
                ]
            ],
        ])
        ->assertJsonFragment([
            'id' => $report->id,
            'branch_office_id' => $this->user->branch_id,
        ]);

});


test('cannot view single report of other branches', function () {
    $report = Report::where('branch_office_id', '!=', $this->user->branch_id)->inRandomOrder()->first();

    $this->actingAs($this->user)
        ->get(route('reports.show', $report->id))
        ->assertStatus(403);
});


test('can create reports', function () {
    $report = Report::factory()->make()->toArray();
    $report['call_date'] = Carbon::parse($report['call_date'])->format('Y-m-d H:i:s');
    $report['work_start_date'] = Carbon::parse($report['work_start_date'])->format('Y-m-d H:i:s');

    $attributes = array_merge($report, [
        'emergency_type' => ['id' => $report['emergency_type']],
        'transport' => [
            [
                'id' => \App\Models\TransportType::inRandomOrder()->first()->id,
                'count' => fake()->numberBetween(1, 10),
            ]
        ]
    ]);

    $response = $this->actingAs($this->user)
        ->postJson(route('reports.store'), [
                "data" => [
                    "type" => "report",
                    "attributes" => $attributes,
                ]
            ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'caller',
                    // etc.
                ]
            ],
        ]);

    unset($report['report_code']);

    $this->assertDatabaseHas('reports', array_merge($report, [
        'id' => $response->json('data.id'),
        'branch_office_id' => $this->user->branch_id,
        'reported_by' => $this->user->id,
    ]))
        ->assertDatabaseHas('transports', [
            'report_id' => $response->json('data.id'),
            'type' => $attributes['transport'][0]['id'],
            'count' => $attributes['transport'][0]['count'],
        ]);

    Transport::latest()->first()->delete();
    Report::latest()->first()->delete();
});


test('can create updates', function () {
    $report = Report::where([
        'branch_office_id' => $this->user->branch_id,
        'is_closed' => false,
    ])->inRandomOrder()->first();

    $update = Update::factory()->make()->toArray();

    $this->actingAs($this->user)
        ->postJson(route('updates.store', $report->id), [
            "data" => [
                "type" => "update",
                "attributes" => $update,
            ]
        ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'report_id',
                    'minor_injury_count',
                    'reported_by',
                    // etc.
                ]
            ],
        ]);

    $this->assertDatabaseHas('updates', array_merge($update, [
        'report_id' => $report->id,
        'reported_by' => $this->user->id,
    ]));
});

//todo:create tests for report closing updates and total sums

test('cannot create updates for closed reports', function () {
    $report = Report::where([
        'branch_office_id' => $this->user->branch_id,
        'is_closed' => true,
    ])->inRandomOrder()->first();

    $update = Update::factory()->make()->toArray();

    $this->actingAs($this->user)
        ->postJson(route('updates.store', $report->id), [
                "data" => [
                    "type" => "update",
                    "attributes" => $update,
                ]
            ])
        ->assertStatus(400);
});


test('cannot create updates for reports from other branches', function () {
    $report = Report::where([
            ['branch_office_id', '!=', $this->user->branch_id],
            ['is_closed', '=', false],
        ])
        ->inRandomOrder()->first();

    $update = Update::factory()->make()->toArray();

    $this->actingAs($this->user)
        ->postJson(route('updates.store', $report->id), [
                "data" => [
                    "type" => "update",
                    "attributes" => $update,
                ]
            ])
        ->assertStatus(403);

});

test('can access count table with own branch', function () {
    $response = $this->actingAs($this->user)
        ->get(route('statistics.count-table'))
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

    expect($response->json('data.cells'))->each(function ($cell) {
        $branch_id = $cell->value['branch_office_id'];
        expect($branch_id)->toBe($this->user->branch_id);
    });

});
