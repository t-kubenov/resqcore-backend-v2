<?php

use function Tests\Helpers\{getRandomCentralAndBranchUsers};

// todo: unit test for the statistics, reports, and departures filters


beforeEach(function () {
    $this->users = getRandomCentralAndBranchUsers();
});


function assertUsersCanAccessEndpoint ($test_instance, $route, $data): void
{
    foreach ($test_instance->users as $user) {
        $test_instance->actingAs($user)
            ->get('/api/v2/' . $route)
            ->assertStatus(200)
            ->assertJsonStructure($data);
    }
} // moved this here from helpers to avoid rewriting everything


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

    assertUsersCanAccessEndpoint($this, 'statistics/card-statistics', $data);
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

    assertUsersCanAccessEndpoint($this, 'statistics/emergency-types-distribution', $data);
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

    assertUsersCanAccessEndpoint($this, 'statistics/emergencies-per-branch-office', $data);
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
                'fatal_injury_total',
                'severe_injury_total',
            ],
        ],
    ];

    assertUsersCanAccessEndpoint($this, 'statistics/injury-counts-per-emergency-type', $data);
});


test('evacuated people medical aid', function () {
    $data = [
        "data" => [
            "evacuated_people_total",
            "provided_medical_total"
        ]
    ];

    assertUsersCanAccessEndpoint($this, 'statistics/evacuated-people-medical-aid', $data);
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

    assertUsersCanAccessEndpoint($this, 'statistics/response-time-per-emergency-type', $data);
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


    assertUsersCanAccessEndpoint($this, 'statistics/worker-man-hours-per-emergency', $data);
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

    assertUsersCanAccessEndpoint($this, 'statistics/total-injuries-impact-over-time', $data);
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

    assertUsersCanAccessEndpoint($this, 'statistics/transport-usage', $data);
});
