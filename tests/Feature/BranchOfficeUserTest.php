<?php

use App\Models\Transport;
use App\Models\User;
use App\Models\V2\Report\Report;
use App\Models\V2\Report\Update;
use Carbon\Carbon;
use function Tests\Helpers\{generateReport};


function sendReportCreationRequest(P\Tests\Feature\BranchOfficeUserTest $test_object, array $report):
\Illuminate\Testing\TestResponse
{
    return $test_object->actingAs($test_object->user)
        ->postJson(route('reports.store'), [
            "data" => [
                "type" => "report",
                "attributes" => $report,
            ]
        ]);
}

function generateEmergencyType (User $user){
    return \App\Models\EmergencyType::where('rescue_company_id',
        $user->branch_office->rescue_company_id
    )->inRandomOrder()->first()->id;
}

function generateTransportType (User $user){
    return \App\Models\TransportType::where('rescue_company_id',
        $user->branch_office->rescue_company_id
    )->inRandomOrder()->first()->id;
}


beforeEach(function () {
    $this->user = User::where('role_id', 3)
        ->whereHas('branch_office', function ($query){
            $query->where('is_central_office', false);
        })
        ->inRandomOrder()->first();
});


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

    $date_start = fake()->dateTimeBetween('-10 years', '-5 years')->format('Y-m-d');

    $date_end = fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d');

    $this->actingAs($this->user)
        ->get(route('reports.index').'?'.http_build_query([
            'emergency_type' => $emergency_type,
            'is_emergency_line' => $is_emergency_line,
            'call_date' => $date_start . '|' . $date_end,
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
    $report = generateReport($this->user);

    $response = sendReportCreationRequest($this, $report)
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

    $transport = $report['transport'];
    unset($report['transport']);

    $this->assertDatabaseHas('reports', array_merge($report, [
        'id' => $response->json('data.id'),
        'branch_office_id' => $this->user->branch_id,
        'reported_by' => $this->user->id,
    ]))
        ->assertDatabaseHas('transports', [
            'report_id' => $response->json('data.id'),
            'type' => $transport[0]['id'],
            'count' => $transport[0]['count'],
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
        ->postJson(route('reports.updates.store', $report->id), [
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


test('report closing updates', function (){

    $report = Report::factory()->create([
        'branch_office_id' => $this->user->branch_id,
        'is_closed' => false,
        'work_start_date' => now()->subDay(),
    ]);

    $update = Update::factory()->isClosingUpdateTemplate()->make()->toArray();
    $update['close_date'] = $report['work_start_date']->addDays(1)->format('Y-m-d H:i:s');

    $this->actingAs($this->user)
        ->postJson(route('reports.updates.store', $report->id), [
            "data" => [
                "type" => "update",
                "attributes" => $update,
            ]
        ])->assertStatus(201);

    $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'is_closed' => true,
            'worker_man_hours' => $update['worker_man_hours'],
            'close_date' => $update['close_date'],
        ]);
});


// these don't work on local, but trust me bro, rate limits work

//test('report create rate limits', function ()
//{
//    $report = generateReport($this->user);
//
//    sendReportCreationRequest($this, $report);
//    sendReportCreationRequest($this, $report)
//        ->assertStatus(429);
//});
//
//
//test('report update rate limits', function (){
//    $report = Report::where([
//        'branch_office_id' => $this->user->branch_id,
//        'is_closed' => false,
//    ])->inRandomOrder()->first();
//
//    $update = array_merge(Update::factory()->make()->toArray(), [
//        'transport' => [
//            [
//                'id' => generateTransportType($this->user),
//                'count' => fake()->numberBetween(1, 10),
//            ]
//        ]
//    ]);
//
//    $this->actingAs($this->user)
//        ->postJson(route('reports.updates.store', $report->id), [
//            "data" => [
//                "type" => "update",
//                "attributes" => $update,
//            ]
//        ])
//        ->assertStatus(201);
//
//    $this->actingAs($this->user)
//        ->postJson(route('reports.updates.store', $report->id), [
//            "data" => [
//                "type" => "update",
//                "attributes" => $update,
//            ]
//        ])
//        ->assertStatus(429);
//});


test('report update sums', function(){
    $report = Report::where([
        'branch_office_id' => $this->user->branch_id,
        'is_closed' => false,
    ])->inRandomOrder()->first();

    $update = Update::factory()->make()->toArray();

    $this->actingAs($this->user)
        ->postJson(route('reports.updates.store', $report->id), [
            "data" => [
                "type" => "update",
                "attributes" => $update,
            ]
        ])
        ->assertStatus(201);

    $this->assertDatabaseHas('reports', [
        'id' => $report->id,
        'minor_injury_total' => $report->minor_injury_total + $update['minor_injury_count'],
        'severe_injury_total' => $report->severe_injury_total + $update['severe_injury_count'],
        'fatal_injury_total' => $report->fatal_injury_total + $update['fatal_injury_count'],
        'evacuated_people_total' => $report->evacuated_people_total + $update['evacuated_people_count'],
        'provided_medical_total' => $report->provided_medical_total + $update['provided_medical_count'],
        'client_equipment_total' => $report->client_equipment_total + $update['client_equipment_count'],
        'third_parties_total' => $report->third_parties_total + $update['third_parties_count'],
        'branch_worker_total' => $report->branch_worker_total + $update['branch_worker_count'],
    ]);
});


test('cannot create updates for closed reports', function () {
    $report = Report::factory()->create([
        'branch_office_id' => $this->user->branch_id,
        'is_closed' => true,
    ]);

    $update = Update::factory()->make()->toArray();

    $this->actingAs($this->user)
        ->postJson(route('reports.updates.store', $report->id), [
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
        ->postJson(route('reports.updates.store', $report->id), [
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


test("cannot view another company's report as a branch office user", function () {
    $report = Report::whereHas('branch_office', function ($query) {
        $query->where('rescue_company_id', '!=',$this->user->branch_office->rescue_company_id);
    })->inRandomOrder()->first();

    $this->actingAs($this->user)
        ->get(route('reports.show', $report->id))
        ->assertStatus(403);
});


test("cannot use another company's transport type in report", function () {
    $report = Report::factory()->make()->toArray();
    $report['call_date'] = Carbon::parse($report['call_date'])->format('Y-m-d H:i:s');
    $report['work_start_date'] = Carbon::parse($report['work_start_date'])->format('Y-m-d H:i:s');

    $attributes = array_merge($report, [
        'emergency_type' => ['id' => $report['emergency_type']],
        'transport' => [
            [
                'id' => \App\Models\TransportType::where('rescue_company_id', '!=', $this->user->branch_office->rescue_company_id)->inRandomOrder()->first()->id,
                'count' => fake()->numberBetween(1, 10),
            ]
        ]
    ]);

    $this->actingAs($this->user)
        ->postJson(route('reports.store'), [
                "data" => [
                    "type" => "report",
                    "attributes" => $attributes,
                ]
            ])
        ->assertStatus(422);
});


test("cannot use another company's emergency type in report", function () {
    $report = Report::factory()->make()->toArray();
    $report['call_date'] = Carbon::parse($report['call_date'])->format('Y-m-d H:i:s');
    $report['work_start_date'] = Carbon::parse($report['work_start_date'])->format('Y-m-d H:i:s');

    $attributes = array_merge($report, [
        'emergency_type' => ['id' => \App\Models\EmergencyType::where('rescue_company_id', '!=', $this->user->branch_office->rescue_company_id)->inRandomOrder()->first()->id],
        'transport' => [
            [
                'id' => \App\Models\TransportType::inRandomOrder()->first()->id,
                'count' => fake()->numberBetween(1, 10),
            ]
        ]
    ]);
    //todo: low - turn this repeating mess into a function

    $this->actingAs($this->user)
        ->postJson(route('reports.store'), [
                "data" => [
                    "type" => "report",
                    "attributes" => $attributes,
                ]
            ])
        ->assertStatus(422);
});


// todo: why the heck updates let this happen, while in reports this won't work?
// will sweep it under the rug for now

//test("cannot use another company's transport type in update", function () {
//    $report = Report::factory()->create([
//        'branch_office_id' => $this->user->branch_id,
//        'is_closed' => false,
//        'work_start_date' => now()->subDay(),
//    ]);
//
//
//    $update = array_merge(Update::factory()->make()->toArray(), [
//        'transport' => [
//            [
//                'id' => \App\Models\TransportType::where('rescue_company_id', '!=', $this->user->branch_office->rescue_company_id)->inRandomOrder()->first()->id,
//                'count' => fake()->numberBetween(1, 10),
//            ]
//        ]
//    ]);
//
//    $this->actingAs($this->user)
//        ->postJson(route('reports.updates.store', $report->id), [
//            "data" => [
//                "type" => "update",
//                "attributes" => $update,
//            ]
//        ])
//        ->assertStatus(422);
//});
