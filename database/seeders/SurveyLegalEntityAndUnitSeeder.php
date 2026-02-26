<?php

namespace Database\Seeders;

use App\Tenant\BranchOffice;
use App\Survey\Models\SurveyLegalEntity;
use App\Survey\Models\SurveyLegalEntityUnit;
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
