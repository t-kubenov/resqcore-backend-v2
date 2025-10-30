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
            // --- Oil & Gas ---
            [
                'key' => 'oil_gas_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов нефтяной и газовой отраслей промышленности',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010250',
            ],
            [
                'key' => 'oil_refinery_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов в нефтехимической, нефтеперерабатывающей отраслях, нефтебаз и автозаправочных станций',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010256',
            ],
            [
                'key' => 'offshore_oil_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов, осуществляющих проведение нефтяных операций на море',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010239',
            ],
            [
                'key' => 'gas_processing_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов по подготовке и переработке газов',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010238',
            ],
            [
                'key' => 'pipeline_safety',
                'name' => 'Правила обеспечения промышленной безопасности при эксплуатации магистральных трубопроводов',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010240',
            ],
            [
                'key' => 'oil_tank_safety',
                'name' => 'Правила обеспечения промышленной безопасности при эксплуатации и ремонте резервуаров для нефти и нефтепродуктов',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V2100023068',
            ],
            [
                'key' => 'oil_pipeline_operation',
                'name' => 'Правила эксплуатации магистральных нефтепроводов',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010107',
            ],
            [
                'key' => 'gas_pipeline_operation',
                'name' => 'Правила эксплуатации магистральных газопроводов',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1500010363',
            ],
            [
                'key' => 'heavy_oil_equipment_safety',
                'name' => 'Инструкция по безопасной эксплуатации оборудования для добычи высоковязкой, сернистой нефти',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V2100023632',
            ],
            [
                'key' => 'gas_supply_system_safety',
                'name' => 'Требования по безопасности объектов систем газоснабжения',
                'alias' => 'oil',
                'link' => 'https://adilet.zan.kz/rus/docs/V1700015986',
            ],

            // --- Coal / Mining ---
            [
                'key' => 'mining_geology_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов, ведущих горные и геологоразведочные работы',
                'alias' => 'coal',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010247',
            ],
            [
                'key' => 'mineral_processing_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов, ведущих работы по переработке твердых полезных ископаемых',
                'alias' => 'coal',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010258',
            ],
            [
                'key' => 'metal_melting_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов по производству расплавов черных, цветных, драгоценных металлов и сплавов на основе этих металлов',
                'alias' => 'coal',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010274',
            ],
            [
                'key' => 'tailings_safety',
                'name' => 'Правила обеспечения промышленной безопасности для хвостовых и шламовых хозяйств опасных производственных объектов',
                'alias' => 'coal',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010253',
            ],
            [
                'key' => 'coal_mine_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов угольных шахт',
                'alias' => 'coal',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010255',
            ],
            [
                'key' => 'explosives_safety',
                'name' => 'Правила обеспечения промышленной безопасности для опасных производственных объектов, ведущих взрывные работы и работы со взрывчатыми материалами промышленного назначения',
                'alias' => 'coal',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010244',
            ],
            [
                'key' => 'mine_plan_instruction',
                'name' => 'Инструкция по составлению плана горных работ',
                'alias' => 'coal',
                'link' => 'https://adilet.zan.kz/rus/docs/V1800016978',
            ],

            // --- General ---
            [
                'key' => 'subsoil_code',
                'name' => 'Кодекс Республики Казахстан "О недрах и недропользовании"',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/K1700000125',
            ],
            [
                'key' => 'civil_protection_law',
                'name' => 'Закон Республики Казахстан "О гражданской защите"',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/Z1400000188',
            ],
            [
                'key' => 'chemical_industry_safety',
                'name' => 'Правил обеспечения промышленной безопасности для опасных производственных объектов химической отрасли промышленности',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010276',
            ],
            [
                'key' => 'pressure_equipment_safety',
                'name' => 'Правила обеспечения промышленной безопасности при эксплуатации оборудования, работающего под давлением',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010303',
            ],
            [
                'key' => 'lifting_mechanism_safety',
                'name' => 'Правила обеспечения промышленной безопасности при эксплуатации грузоподъемных механизмов',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010332',
            ],
            [
                'key' => 'compressor_station_safety',
                'name' => 'Правила обеспечения промышленной безопасности при эксплуатации компрессорных станций',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010251',
            ],
            [
                'key' => 'emergency_service_rules',
                'name' => 'Правила обслуживания организаций, владеющих и (или) эксплуатирующих опасные производственные объекты, профессиональными аварийно-спасательными службами в области промышленной безопасности',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V1400010252',
            ],
            [
                'key' => 'training_rules',
                'name' => 'Правила подготовки, переподготовки и проверки знаний специалистов, работников в области промышленной безопасности',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V2100023461',
            ],
            [
                'key' => 'fire_safety',
                'name' => 'Правила пожарной безопасности',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V2200026867',
            ],
            [
                'key' => 'fire_safety_regulation',
                'name' => 'Технический регламент «Общие требования к пожарной безопасности»',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V2100024045',
            ],
            [
                'key' => 'emergency_plan_instruction',
                'name' => 'Инструкция по разработке плана ликвидации аварий и проведению учебных тревог и противоаварийных тренировок на опасных производственных объектах',
                'alias' => 'general',
                'link' => 'https://adilet.zan.kz/rus/docs/V2100023684',
            ],
        ];


        foreach ($clauses as $clause){
            ViolationClauseType::updateOrCreate(['key' => $clause['key']], $clause);
        }
    }
}
