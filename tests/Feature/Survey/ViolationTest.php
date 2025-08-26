<?php
use App\Models\BranchOffice;
use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyFacilitySpokesman;
use App\Models\Survey\SurveyLegalEntity;
use App\Models\Survey\SurveyLegalEntityUnit;
use App\Models\Survey\SurveySender;
use App\Models\Survey\Violation;
use function Tests\Helpers\{getRandomCentralAndBranchUsers, getRandomBranchIdFromCentralOffice};


beforeEach(function() {
    $this->users = getRandomCentralAndBranchUsers();

    $this->survey = Survey::factory()->create([
        'branch_office_id' => $this->users['branch_user']['branch_id'],
    ]);

    $this->centralOfficeSurvey = Survey::factory()->create([
        'branch_office_id' => getRandomBranchIdFromCentralOffice($this->users['central_user']['branch_id']),
    ]);

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
        'data' => [
            'id',
            'type',
            'attributes' => [
                'description',
                'clause',
                'elimination_prescription_date',
                'elimination_completion',
                'elimination_completion_date',
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


function formatViolationDataForCreate(Violation $violation): array
{
    return [
        'data' => [
            'attributes' => [
                'survey_id' => $violation['survey_id'],
                'description' => $violation['description'],
                'clause' => $violation['clause'],
                'elimination_prescription_date' => $violation['elimination_prescription_date']->format('Y-m-d'),
                'elimination_completion' => $violation['elimination_completion'],
                'elimination_completion_date' => $violation['elimination_completion_date']->format('Y-m-d'),
            ]
        ]
    ];
}


function getDifferentBranchOfficeId(int $branch_office_id): int
{
    return BranchOffice::where('id', '!=', $branch_office_id)
        ->where('is_central_office', false)
        ->first()
        ->id;
}

function getDifferentCompanyBranchOfficeId(int $rescue_company_id): int
{
    return BranchOffice::where('rescue_company_id', '!=', $rescue_company_id)
        ->where('is_central_office', false)
        ->first()
        ->id;
}


it('creates a violation', function ()
{
    $violation = Violation::factory()->make();

    $this
        ->actingAs($this->users['branch_user'])
        ->postJson(route('surveys.violations.store', $this->survey->id), formatViolationDataForCreate($violation))
        ->assertStatus(201);
});


it('shows a violation to branch office users', function () {
    $violation = Violation::factory()->create([
        'survey_id' => $this->survey->id,

    ]);

    $this
        ->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.violations.show', $violation->id))
        ->assertStatus(200)
        ->assertJsonStructure($this->showStructure);
});


it('shows a violation to central office users', function () {
    $violation = Violation::factory()->create([
        'survey_id' => $this->centralOfficeSurvey->id,
    ]);

    $this
        ->actingAs($this->users['central_user'])
        ->getJson(route('surveys.violations.show', $violation->id))
        ->assertStatus(200)
        ->assertJsonStructure($this->showStructure);
});


it('lists violations for a survey for branch office users', function () {
    $this
        ->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.violations.index', $this->survey->id))
        ->assertStatus(200)
        ->assertJsonStructure($this->indexStructure);
});


it('lists violations for a survey for central office users', function () {
    $this
        ->actingAs($this->users['central_user'])
        ->getJson(route('surveys.violations.index', $this->centralOfficeSurvey->id))
        ->assertStatus(200)
        ->assertJsonStructure($this->indexStructure);
});


it('closes a violation for branch office users', function () {
    $violation = Violation::factory()->create([
        'survey_id' => $this->survey->id,
        'elimination_completion' => false,
    ]);

    $data = [
        'data' => [
            'attributes' => [
                'elimination_completion_date' => now()->format('Y-m-d'),
            ]
        ]
    ];

    $this
        ->actingAs($this->users['branch_user'])
        ->postJson(route('surveys.violations.close', $violation->id), $data)
        ->assertStatus(200)
        ->assertJsonStructure($this->showStructure);
});


it('forbids closing a violation for central office users', function () {
    $violation = Violation::factory()->create([
        'survey_id' => $this->centralOfficeSurvey->id,
        'elimination_completion' => false,
    ]);

    $data = [
        'data' => [
            'attributes' => [
                'elimination_completion_date' => now()->format('Y-m-d'),
            ]
        ]
    ];

    $this
        ->actingAs($this->users['central_user'])
        ->postJson(route('surveys.violations.close', $violation->id), $data)
        ->assertStatus(403);
});


it('forbids closing an already closed violation', function () {
    $violation = Violation::factory()->create([
        'survey_id' => $this->survey->id,
        'elimination_completion' => true,
    ]);

    $data = [
        'data' => [
            'attributes' => [
                'elimination_completion_date' => now()->format('Y-m-d'),
            ]
        ]
    ];

    $this
        ->actingAs($this->users['branch_user'])
        ->postJson(route('surveys.violations.close', $violation->id), $data)
        ->assertStatus(422)
        ->assertJson(['message' => __('validation.survey.violations.close.already_closed')]);
});


it('forbids seeing violations for a survey that does not belong to the user\'s branch office', function () {
    $survey = Survey::factory()
        ->has(Violation::factory()->count(1), 'violations')
        ->create([
            'branch_office_id' => getDifferentBranchOfficeId($this->users['branch_user']['branch_id']),
        ]);

    $this
        ->actingAs($this->users['branch_user'])
        ->getJson(route('surveys.violations.show', $survey->violations[0]->id))
        ->assertStatus(403);
});


it('forbids seeing violations for a survey that does not belong to the user\'s company', function () {
    $survey = Survey::factory()
        ->has(Violation::factory()->count(1), 'violations')
        ->create([
            'branch_office_id' => getDifferentCompanyBranchOfficeId($this->users['central_user']['rescue_company_id']),
        ]);

    $this
        ->actingAs($this->users['central_user'])
        ->getJson(route('surveys.violations.show', $survey->violations[0]->id))
        ->assertStatus(403);
});
