<?php

return [
    'print' => [
        'header' => [
            'branch_office_id' => [
                'name' => '',
                'alias' => '',
            ],
            'survey_date' => [
                'name' => '',
                'alias' => '',
            ],
        ],
        'columns' => [
            'survey_code' => [
                'name' => '',
                'alias' => '',
            ],
            'senders' => [
                'name' => 'Мен/Біз/Мною/Нами',
                'alias' => '',
            ],
            'spokesmen' => [
                'name' => 'Қатысуымен/В присутствии',
                'alias' => '',
            ],
            'hazardous_facility_name' => [
                'name' => 'Тексеру жүргізілді/Произведено обследование',
                'alias' => '',
            ],
        ],
        'table_columns' => [
            'description' => [
                'name' => 'Анықталған бұзушылықтар және оларды жою туралы ұсынымдар/Выявленные нарушения и рекомендации по их устранению',
                'alias' => '',
            ],
//            'discrepancy_no' => [
//                'name' => 'Алу сан/Номер отклонения',
//                'alias' => '',
//            ],
            'clause' => [
                'name' => 'Нормативтік құжаттың тармағына сілтеме/Ссылка на пункт нормативного документа',
                'alias' => '',
            ],
            'elimination_prescription_date' => [
                'name' => 'Анықталған бұзушылықтарды жою мерзімі/Срок устранения выявленного нарушения',
                'alias' => '',
            ],
            'elimination_completion_date' => [
                'name' => 'Бұзушылықтардың жойылғандығы жөніндегі белгі, күні/Отметка об устранении нарушения, дата',
                'alias' => '',
            ],
        ],
    ],

    'fields' => [
        'branch_office_id' => [
            'name' => 'Наименование филиала',
            'alias' => '',
        ],
        'created_by' => [
            'name' => 'Создал(-а) донесение',
            'alias' => '',
        ],
        'legal_entity_id' => [
            'name' => 'Наименование юридического лица',
            'alias' => '',
        ],
        'legal_entity_unit_id' => [
            'name' => 'Подразделение юридического лица',
            'alias' => '',
        ],
        'survey_code' => [
            'name' => 'Номер акта проф. обследования',
            'alias' => 'Номер акта',
        ],
        'survey_date' => [
            'name' => 'Дата обследования',
            'alias' => '',
        ],
        'hazardous_facility_name' => [
            'name' => 'Наименование опасного производственного объекта',
            'alias' => '',
        ],

        'spokesmen' => [
            'name' => 'Представители ОПО',
            'alias' => '',
        ],

        'position' => [
            'name' => 'Должность',
            'alias' => '',
        ],

        'senders' => [
            'name' => 'Проверяющие',
            'alias' => '',
        ],

        'legal_entity' => [
            'name' => 'Наименование юридического лица',
            'alias' => '',
        ],

        'legal_entity_unit' => [
            'name' => 'Подразделение юр. лица',
            'alias' => '',
        ],

        'violations' => [
            'survey_id' => [
                'name' => 'ID акта проф. обследования',
                'alias' => 'ID проф. обследования',
            ],
            'description' => [
                'name' => 'Выявленные нарушения и рекомендации по их устранению',
                'alias' => 'Выявленные нарушения',
            ],
            'clause_type' => [
                'name' => 'Нормативный документ',
                'alias' => '',
            ],
            'clause' => [
                'name' => 'Ссылка на пункт нормативного документа',
                'alias' => '',
            ],
            'elimination_prescription_date' => [
                'name' => 'Срок устранения выявленного нарушения',
                'alias' => '',
            ],
            'elimination_completion' => [
                'name' => 'Нарушение устранено',
                'alias' => '',
            ],
            'elimination_completion_date' => [
                'name' => 'Дата устранения нарушения',
                'alias' => '',
            ],
        ],
    ],

];
