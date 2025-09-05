<?php

namespace Database\Seeders\Main;

use App\Models\Survey\ViolationClauseType;
use Illuminate\Database\Seeder;

class ViolationClauseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clauses = [
            [
                'key' => 'oil_gas_safety',
                'name' => 'Правила обеспечения промышленной безопасности для нефтяной и газовой отраслей',
                'alias' => '',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010250',
            ],
            [
                'key' => 'mining_geology_safety',
                'name' => 'Правила обеспечения промышленной безопасности при горных и геологоразведочных работах',
                'alias' => '',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010247',
            ],
            [
                'key' => 'explosives_safety',
                'name' => 'Правила обеспечения промышленной безопасности при взрывных работах и работе с взрывчатыми материалами',
                'alias' => '',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010244',
            ],
            [
                'key' => 'gas_processing_safety',
                'name' => 'Правила обеспечения промышленной безопасности при подготовке и переработке газов',
                'alias' => '',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010238',
            ],
            [
                'key' => 'fire_safety',
                'name' => 'Правила пожарной безопасности',
                'alias' => '',
                'link' => 'https://adilet.zan.kz/rus/docs/V2200026867',
            ],
        ];

        foreach ($clauses as $clause){
            ViolationClauseType::updateOrCreate($clause);
        }
    }
}
