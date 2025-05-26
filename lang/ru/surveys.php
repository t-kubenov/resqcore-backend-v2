<?php

return [
    'fields' => [
        'branch_office_id' => [
            'name' => 'Наименование филиала',
            'alias' => 'Наименование филиала',
        ],
        'created_by' => [
            'name' => 'Создал донесение',
            'alias' => 'Создал донесение',
        ],
        'legal_entity_id' => [
            'name' => 'Наименование юридического лица',
            'alias' => 'Наименование юридического лица',
        ],
        'legal_entity_unit_id' => [
            'name' => 'Наименование юридического лица',
            'alias' => 'Подразделение юридического лица',
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

        'violations' => [
            'survey_id' => [
                'name' => 'ID акта проф. обследования',
                'alias' => 'ID проф. обследования',
            ],
            'description' => [
                'name' => 'Выявленные нарушения и рекомендации по их устранению',
                'alias' => 'Выявленные нарушения',
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

        'spokesmen' => [
            'name' => 'Представители ОПО',
            'alias' => '',
        ],

        'senders' => [
            'name' => 'Подающие',
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
    ],

];
