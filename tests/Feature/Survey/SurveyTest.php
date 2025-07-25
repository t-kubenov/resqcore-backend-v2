<?php

use App\Models\BranchOffice;
use App\Models\User;
use App\Models\V2\Survey\Survey;
use App\Models\V2\Survey\SurveyFacilitySpokesman;
use App\Models\V2\Survey\SurveyLegalEntity;
use App\Models\V2\Survey\SurveyLegalEntityUnit;
use App\Models\V2\Survey\SurveySender;
use function Tests\Helpers\{getRandomCentralAndBranchUsers, getRandomBranchIdFromCentralOffice};


function generateSurvey($user): array
{
    $legalEntity = SurveyLegalEntity::where('branch_office_id', $user->branch_id)->inRandomOrder()->first();
    $legalEntityUnit = SurveyLegalEntityUnit::where('legal_entity_id', $legalEntity?->id)->inRandomOrder()->first();

    return [
        'data' => [
            'type' => Survey::class,
            'attributes' => [
                'senders' => collect(range(1, 4))->map(fn() => fake()->name())->all(),
                'spokesmen' => collect(range(1, 6))->map(fn() => fake()->name())->all(),
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
                    '*' => [ // todo: get rid of this, solve in SurveyCollection
                        'survey_code',
                        // etc.
                    ]
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
                '*' => [ // todo: get rid of this, solve in SurveyCollection
                    'survey_code',
                    // etc.
                ]
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
    unset($survey['data']['attributes']['senders'], $survey['data']['attributes']['spokesmen']);

    $response->assertStatus(201);

    // Check if the database is storing the surveys
    $this->assertDatabaseHas('surveys', $survey['data']['attributes']);

    foreach($senders as $sender) {
        $this->assertDatabaseHas('survey_senders', [
            'survey_id' => $response->json('data.id'),
            'name' => $sender,
        ]);
    }

    foreach($spokesmen as $spokesman) {
        $this->assertDatabaseHas('survey_facility_spokesman', [
            'survey_id' => $response->json('data.id'),
            'name' => $spokesman,
        ]);
    }
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
        ->assertJsonStructure($this->showStructure)
        ->assertOk();
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
        ->assertJsonStructure($this->showStructure)
        ->assertOk();
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

    dump($survey->branch_office_id, $this->users['branch_user']['branch_id'], $this->users['branch_user']['access_level']);

    $this->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.show', $survey->id))
        ->assertForbidden();
});


it('forbids showing a survey to a central office user from another company', function () {
    $central_id = $this->users['central_user']['branch_id'];
    $branch_id = getDifferentCompanyBranchOfficeId($central_id);

    $survey = Survey::factory()->create([
        'branch_office_id' => $branch_id,
    ]);
        // todo: this is a critical ahh bug, fix it
    $this->actingAs($this->users['central_user'])
        ->getJson(route('surveys.show', $survey->id))
        ->assertForbidden();
});



// showing index and show for different branch office and company users
// printing and exporting for same cases,
// aaand we're good to go


