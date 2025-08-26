<?php

use App\Models\User;
use App\Models\Report\Report;
use App\Models\Report\Update;
use function Tests\Helpers\generateReport;


beforeEach(function () {
    $this->user = User::where('role_id', 2)->inRandomOrder()->first();
});


test('can see reports by id', function () {
    $report = Report::whereHas('branch_office', function($query){
        return $query->where('rescue_company_id', $this->user->branch_office->rescue_company_id);
    })->inRandomOrder()->first();

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
    $report = generateReport($this->user);

    $this->actingAs($this->user)
        ->post(route('reports.store'), [
            'data' => [
                'attributes' => $report
            ]
        ])->assertStatus(403);
});


test('cannot create updates', function () {
    $report = Report::where('is_closed', false)->inRandomOrder()->first();
    $update = Update::factory()->make()->toArray();

    $this->actingAs($this->user)
        ->post(route('reports.updates.store', $report->id),  [
            'data' => [
                'attributes' => $update
            ]
        ])
        ->assertStatus(403);
});


test('can see all reports of own company', function (){
    $this->actingAs($this->user)
        ->get(route('reports.index'))
        ->assertStatus(200)
        ->assertJsonFragment([
            'total' => Report::whereHas('branch_office', function ($query) {
                $query->where('rescue_company_id', $this->user->rescue_company_id);
            })->count(),
        ]);
});


test("cannot view another company's report as a central office user", function () {
    $report = Report::whereHas('branch_office', function ($query) {
        $query->where('rescue_company_id', '!=',$this->user->branch_office->rescue_company_id);
    })->inRandomOrder()->first();

    $this->actingAs($this->user)
        ->get(route('reports.show', $report->id))
        ->assertStatus(403);
});
