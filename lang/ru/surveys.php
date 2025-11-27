<?php

return [
    'print' => [
        'header' => [
            'branch_office_id' => [
                'name' => '',
            ],
            'survey_date' => [
                'name' => '',
            ],
        ],
        'columns' => [
            'senders' => [
                'name' => 'Мен/Біз/Мною/Нами',
            ],
            'spokesmen' => [
                'name' => 'Қатысуымен/В присутствии',
            ],
            'hazardous_facility_name' => [
                'name' => 'Тексеру жүргізілді/Произведено обследование',
            ],
        ],
        'table_columns' => [
            'description' => [
                'name' => 'Анықталған бұзушылықтар және оларды жою туралы ұсынымдар/Выявленные нарушения и рекомендации по их устранению',
            ],
            'clause' => [
                'name' => 'Нормативтік құжаттың тармағына сілтеме/Ссылка на пункт нормативного документа',
            ],
            'elimination_prescription_date' => [
                'name' => 'Анықталған бұзушылықтарды жою мерзімі/Срок устранения выявленного нарушения',
            ],
            'elimination_completion_date' => [
                'name' => 'Бұзушылықтардың жойылғандығы жөніндегі белгі, күні/Отметка об устранении нарушения, дата',
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
            'name' => 'Представители объекта',
            'alias' => '',
        ],

        'position' => [
            'name' => 'Должность',
            'alias' => '',
        ],

        'reviewer' => [
            'name' => 'С актом ознакомлен',
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
