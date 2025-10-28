<?php

use App\Models\BranchOffice;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyLegalEntity;
use App\Models\Survey\SurveyLegalEntityUnit;
use function Tests\Helpers\{getRandomCentralAndBranchUsers, getRandomBranchIdFromCentralOffice};


function generateSurvey($user): array
{
    $legalEntity = SurveyLegalEntity::factory()->create(['branch_office_id' => $user->branch_id]);
    $legalEntityUnit = SurveyLegalEntityUnit::factory()->create(['legal_entity_id' => $legalEntity->id]);

    return [
        'data' => [
            'type' => Survey::class,
            'attributes' => [
                'senders' => collect(range(1, 4))
                    ->map(fn() => ['name' => fake()->name(), 'position' => fake()->word()])->all(),
                'spokesmen' => collect(range(1, 6))
                    ->map(fn() => ['name' => fake()->name(), 'position' => fake()->word()])->all(),
                'reviewer' => [['name' => fake()->name(), 'position' => fake()->word()]],
                'legal_entity_id' => $legalEntity?->id,
                'legal_entity_unit_id' => $legalEntityUnit?->id,
                'survey_date' => now()->subDay()->format('Y-m-d'),
                'hazardous_facility_name' => fake()->company(),
            ]
        ]
    ];
}


beforeEach(function() {
    $this->users = getRandomCentralAndBranchUsers();

    $this->indexStructure = [
        'columns' => [
            '*' => [
                'key',
                'name',
            ]
        ],
        'data' => [
            '*' => [
                'id',
                'type',
                'attributes' => [
                    'survey_code',
                    // etc.
                ]
            ]
        ],
    ];

    $this->showStructure = [
        'columns' => [
            '*' => [
                'key',
                'name',
            ]
        ],
        'data' => [
            'id',
            'type',
            'attributes' => [
                'survey_code',
                // etc.
            ]
        ]
    ];

    $this->printStructure = [
        'columns' => [
            '*' => [
                'key',
                'name',
            ]
        ],
        'data' => [
            'id',
            'type',
            'attributes' => [
                'survey_code',
               // etc.
            ]
        ]
    ];
});


it('creates a survey', function () {
    $survey = generateSurvey($this->users['branch_user']);

    $response = $this->actingAs($this->users['branch_user'])
        ->postJson(route('surveys.store'), $survey);

    $senders = $survey['data']['attributes']['senders'];
    $spokesmen = $survey['data']['attributes']['spokesmen'];
    $reviewer = $survey['data']['attributes']['reviewer'];

    unset(
        $survey['data']['attributes']['senders'],
        $survey['data']['attributes']['spokesmen'],
        $survey['data']['attributes']['reviewer'],
    );

    $response->assertStatus(201);

    // Check if the database is storing the surveys
    $this->assertDatabaseHas('surveys', $survey['data']['attributes']);

    foreach($senders as $sender) {
        $this->assertDatabaseHas('survey_participants', [
            'survey_id' => $response->json('data.id'),
            'name' => $sender,
            'type' => 'sender',
        ]);
    }

    foreach($spokesmen as $spokesman) {
        $this->assertDatabaseHas('survey_participants', [
            'survey_id' => $response->json('data.id'),
            'name' => $spokesman,
            'type' => 'spokesman',
        ]);
    }

    $this->assertDatabaseHas('survey_participants', [
        'survey_id' => $response->json('data.id'),
        'name' => $reviewer,
        'type' => 'reviewer',
    ]);
});


it('returns a list of surveys for central office users', function () {
    // show all surveys for the rescue company, don't test filters
    $response = $this->actingAs($this->users['central_user'])
        ->getJson(route('surveys.index'));

    $total = Survey::whereHas('branch_office', function($q){
        $q->where('rescue_company_id', $this->users['central_user']['rescue_company_id']);
    })->count();

    $response->assertStatus(200)
        ->assertJsonStructure($this->indexStructure)
        ->assertJsonFragment(['total' => $total]);
});


it('returns a list of surveys for branch office users', function ()
{
    $response = $this->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.index'));

    $total = Survey::where('branch_office_id', $this->users['branch_user']['branch_id'])->count();

    $response->assertJsonFragment(['total' => $total]);
});


it('shows a specific survey for a branch office user', function()
{
    $survey = Survey::factory()->create([
        'branch_office_id' => $this->users['branch_user']['branch_id'],
    ]);

    $this->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.show', $survey['id']))
        ->assertOk()
        ->assertJsonStructure($this->showStructure);
});


it('shows a specific survey for a central office user', function()
{
    $central_id = $this->users['central_user']['branch_id'];
    $branch_id = getRandomBranchIdFromCentralOffice($central_id);

    $survey = Survey::factory()->create([
        'branch_office_id' => $branch_id,
    ]);

    $this->actingAs($this->users['central_user'])
        ->getJson(route('surveys.show', $survey['id']))
        ->assertOk()
        ->assertJsonStructure($this->showStructure);
});


it('shows print data for a specific survey for a branch office user', function()
{
    $survey = Survey::factory()->create([
        'branch_office_id' => $this->users['branch_user']['branch_id'],
    ]);

    $this->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.print', $survey['id']))
        ->assertJsonStructure($this->printStructure)
        ->assertOk();
});


it('shows print data for a specific survey for a central office user', function()
{
    $central_id = $this->users['central_user']['branch_id'];
    $branch_id = getRandomBranchIdFromCentralOffice($central_id);

    $survey = Survey::factory()->create([
        'branch_office_id' => $branch_id,
    ]);

    $this->actingAs($this->users['central_user'])
        ->getJson(route('surveys.print', $survey['id']))
        ->assertJsonStructure($this->printStructure)
        ->assertOk();
});


it('returns a list of surveys for export for central office users', function () {
    $response = $this->actingAs($this->users['central_user'])
        ->getJson(route('surveys.export'));

    $total = Survey::whereHas('branch_office', function($q){
        $q->where('rescue_company_id', $this->users['central_user']['rescue_company_id']);
    })->count();

    $response->assertStatus(200)
        ->assertJsonStructure($this->indexStructure)
        ->assertJsonFragment(['total' => $total]);
});


it('returns a list of surveys for export for branch office users', function ()
{
    $response = $this->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.export'));

    $total = Survey::where('branch_office_id', $this->users['branch_user']['branch_id'])->count();

    $response->assertStatus(200)
        ->assertJsonStructure($this->indexStructure)
        ->assertJsonFragment(['total' => $total]);
});


it('forbids creating a survey for central office users', function () {
    $survey = generateSurvey($this->users['central_user']);

    $this->actingAs($this->users['central_user'])
        ->postJson(route('surveys.store'), $survey)
        ->assertForbidden();
});


it('forbids creating surveys with unmatching legal entity and unit', function () {
    $survey = generateSurvey($this->users['branch_user']);
    $survey['data']['attributes']['legal_entity_unit_id'] = SurveyLegalEntityUnit::factory()->create()->id;
    $survey['data']['attributes']['legal_entity_id'] = SurveyLegalEntity::factory()->create()->id;

    $this->actingAs($this->users['branch_user'])
        ->postJson(route('surveys.store'), $survey)
        ->assertStatus(422);
});


it('forbids showing a survey to a branch office user from another branch', function () {
    $survey = Survey::factory()->create([
        'branch_office_id' => BranchOffice::factory()->create([
            'rescue_company_id' => $this->users['branch_user']['rescue_company_id'],
        ])->id,
    ]);

    $this->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.show', $survey->id))
        ->assertForbidden();
});


it('forbids showing a survey to a central office user from another company', function () {
    $company_id = $this->users['central_user']['rescue_company_id'];
    $branch_id = getDifferentCompanyBranchOfficeId($company_id);

    $survey = Survey::factory()->create([
        'branch_office_id' => $branch_id,
    ]);

    $this->actingAs($this->users['central_user'])
        ->getJson(route('surveys.show', $survey->id))
        ->assertForbidden();
});
