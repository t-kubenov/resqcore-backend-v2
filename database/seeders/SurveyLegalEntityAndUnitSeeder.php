<?php

namespace Database\Seeders;

use App\Models\BranchOffice;
use App\Models\Survey\SurveyLegalEntity;
use App\Models\Survey\SurveyLegalEntityUnit;
use Illuminate\Database\Seeder;

class SurveyLegalEntityAndUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BranchOffice::where('is_central_office', false)->each(
            function (BranchOffice $branch){
            SurveyLegalEntity::factory()
                ->count(3)
                ->create([
                    'branch_office_id' => $branch->id,
                ])
                ->each(
                    function (SurveyLegalEntity $entity) {
                    SurveyLegalEntityUnit::factory()
                        ->count(3)
                        ->create([
                            'legal_entity_id' => $entity->id,
                        ]);
                });
        });
    }
}
