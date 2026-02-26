<?php

namespace Database\Seeders;

use App\Tenant\BranchOffice;
use App\Tenant\User;
use App\Survey\Models\Survey;
use App\Survey\Models\SurveyFacilitySpokesman;
use App\Survey\Models\SurveyLegalEntity;
use App\Survey\Models\SurveyLegalEntityUnit;
use App\Survey\Models\SurveySender;
use App\Survey\Violation\Violation;
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
