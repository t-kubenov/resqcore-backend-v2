<?php

use App\Models\User;


// todo: unit test for the statistics, reports, and departures filters


beforeEach(function (){
    $this->branch_user = User::where('role_id', 3)->inRandomOrder()->first();
    $this->central_user = User::where('role_id', 2)->inRandomOrder()->first();

    $this->users = [$this->branch_user, $this->central_user];
});


function assertStatisticsEndpoint ($object, $route, $data): void
{
    foreach ($object->users as $user) {
        $object->actingAs($user)
            ->get('/api/statistics/' . $route)
            ->assertStatus(200)
            ->assertJsonStructure($data);
    }
}


test('card statistics', function () {
    $data = [
        'data' => [
            'total_reports',
            'open_reports',
            'closed_reports',
            'worker_hours_total',
            'workers_total',
            'emergency_line_reports',
            'contract_reports',
        ]
    ];

    assertStatisticsEndpoint($this, 'card-statistics', $data);
});


test('emergency types distribution', function () {
    $data = [
        'data' => [
            '*' => [
                'id',
                'key',
                'name',
                'count',
            ]
        ]
    ];

    assertStatisticsEndpoint($this, 'emergency-types-distribution', $data);
});


test('emergencies per branch office', function () {
    $data = [
        'data' => [
            '*' => [
                'id',
                'key',
                'name',
                'alias',
                'count',
            ]
        ]
    ];

    assertStatisticsEndpoint($this, 'emergencies-per-branch-office', $data);
});


test('injury counts per emergency type', function () {
    $data = [
        'data' => [
            '*' => [
                'emergency_type' => [
                    'id',
                    'key',
                    'name',
                ],
                'minor_injury_total',
                'severe_injury_total',
                'fatal_injury_total',
            ],
        ],
    ];

    assertStatisticsEndpoint($this, 'injury-counts-per-emergency-type', $data);
});


test('evacuated people medical aid', function () {
    $data = [
        "data" => [
            "evacuated_people_total",
            "provided_medical_total"
        ]
    ];

    assertStatisticsEndpoint($this, 'evacuated-people-medical-aid', $data);
});


test('response time per emergency type', function () {
    $data = [
        'data' => [
            '*' => [
                'emergency_type' => [
                    'id',
                    'key',
                    'name',
                ],
                'avg_response_time',
            ],
        ],
    ];

    assertStatisticsEndpoint($this, 'response-time-per-emergency-type', $data);
});


test('worker man hours per emergency', function () {
    $data = [
        'data' => [
            '*' => [
                'emergency_type' => [
                    'id',
                    'key',
                    'name',
                ],
                'worker_man_hours',
            ],
        ],
    ];


    assertStatisticsEndpoint($this, 'worker-man-hours-per-emergency', $data);
});


test('total injuries impact over time', function () {
    $data = [
        'data' => [
            '*' => [
                "call_date",
                "cumulative_minor_injury_total",
                "cumulative_severe_injury_total",
                "cumulative_fatal_injury_total",
                "cumulative_evacuated_people_total",
                "cumulative_provided_medical_total",
            ],
        ],
    ];

    assertStatisticsEndpoint($this, 'total-injuries-impact-over-time', $data);
});


test('transport usage', function () {
    $data = [
        'data' => [
            '*' => [
                "type",
                "name",
                "total_count",
            ],
        ],
    ];

    assertStatisticsEndpoint($this, 'transport-usage', $data);
});
