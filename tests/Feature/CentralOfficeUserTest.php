<?php

use App\Models\Report;
use App\Models\User;


beforeEach(function () {
    $this->user = User::where('role_id', 2)->inRandomOrder()->first();
});


test('can see reports by id', function () {
    $report = Report::inRandomOrder()->first();

    $this->actingAs($this->user)
        ->get(route('reports.show', $report->id))
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
        ])->assertJsonFragment([
            'id' => $report->id,
        ]);
});


it('returns 404 for non-existent report', function () {
    $this->actingAs($this->user)
        ->getJson(route('reports.show', 9999))
        ->assertNotFound();
});


test('can access count table endpoint', function () {
    $this->actingAs($this->user)
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
});


test('cannot create reports', function () {
    $this->actingAs($this->user)
        ->post(route('reports.store'), [])
        ->assertStatus(403);
});


test('cannot create updates', function () {
    $report = Report::where('is_closed', false)->inRandomOrder()->first();

    $this->actingAs($this->user)
        ->post(route('updates.store', $report->id), [])
        ->assertStatus(403);
});
