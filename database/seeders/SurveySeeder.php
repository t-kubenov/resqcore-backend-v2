<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyFacilitySpokesman;
use App\Models\Survey\SurveyLegalEntity;
use App\Models\Survey\SurveyLegalEntityUnit;
use App\Models\Survey\SurveySender;
use App\Models\Survey\Violation;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BranchOffice::where('is_central_office', false)->each(function (BranchOffice $branch) {

            Survey::factory()
                ->count(3)
                ->create(
                    function () use ($branch) {

                        $user = User::where([['role_id', 3]])->inRandomOrder()->first();
                        $legalEntity = SurveyLegalEntity::where('branch_office_id', $branch->id)->inRandomOrder()->first();
                        $legalEntityUnit = SurveyLegalEntityUnit::where('legal_entity_id', $legalEntity->id)->inRandomOrder()->first();

                        return [
                            'branch_office_id' => $branch->id,
                            'created_by' => $user->id,
                            'legal_entity_id' => $legalEntity->id,
                            'legal_entity_unit_id' => $legalEntityUnit->id,
                        ];
                    }
                )
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
