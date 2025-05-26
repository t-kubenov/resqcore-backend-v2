<?php

namespace Database\Seeders\V2\Survey;

use App\Models\BranchOffice;
use App\Models\User;
use App\Models\V2\Survey\Survey;
use App\Models\V2\Survey\SurveyFacilitySpokesman;
use App\Models\V2\Survey\SurveyLegalEntity;
use App\Models\V2\Survey\SurveyLegalEntityUnit;
use App\Models\V2\Survey\SurveySender;
use App\Models\V2\Survey\Violation;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BranchOffice::where('is_central_office', false)->each(function (BranchOffice $branch) {

            $user = User::where([['role_id', 3]])->inRandomOrder()->first();
            $legalEntity = SurveyLegalEntity::where('branch_office_id', $branch->id)->inRandomOrder()->first();
            $legalEntityUnit = SurveyLegalEntityUnit::where('legal_entity_id', $legalEntity->id)->inRandomOrder()->first();

            Survey::factory()
                ->count(3)
                ->create([
                    'branch_office_id' => $branch->id,
                    'created_by' => $user->id,
                    'legal_entity_id' => $legalEntity->id,
                    'legal_entity_unit_id' => $legalEntityUnit->id,
                ])
                ->each(function (Survey $survey) {
                    Violation::factory()
                        ->count(3)
                        ->create([
                            'survey_id' => $survey->id,
                        ]);
                    SurveySender::factory()
                        ->count(3)
                        ->create([
                        'survey_id' => $survey->id,
                        ]);
                    SurveyFacilitySpokesman::factory()
                        ->count(3)
                        ->create([
                        'survey_id' => $survey->id,
                    ]);
                });
        });
    }
}
