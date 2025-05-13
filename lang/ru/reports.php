<?php

return [
    'fields' => [
        'id' => [
            'name' => 'ID',
            'alias' => '',
        ],
        'branch_office' => [
            'name' => 'Филиал',
            'alias' => '',
        ],
        'branch_office_id' => [
            'name' => 'ID филиала',
            'alias' => 'Филиал',
        ],
        'facility_id' => [
            'name' => 'ID объекта',
            'alias' => 'Объект',
        ],
        'facility_name' => [
            'name' => 'Название объекта',
            'alias' => '',
        ],
        'facility_owner_id' => [
            'name' => 'ID объекта',
            'alias' => 'Объект',
        ],
        'facility_owner' => [
            'name' => 'Наименование объекта',
            'alias' => '',
        ],
        'reported_by' => [
            'name' => 'Кто отправил донесение',
            'alias' => '',
        ],
        'report_code' => [
            'name' => 'Номер отчета',
            'alias' => '',
        ],
        'caller' => [
            'name' => 'Кто произвел вызов',
            'alias' => '',
        ],
        'line_facility_location' => [
            'name' => 'Местоположение объекта',
            'alias' => '',
        ],
        'line_facility_owner' => [
            'name' => 'Наименование объекта',
            'alias' => '',
        ],
        'emergency_location' => [
            'name' => 'Место ЧС',
            'alias' => '',
        ],
        'emergency_info' => [
            'name' => 'Данные по аварии',
            'alias' => '',
        ],
        'call_date' => [
            'name' => 'Дата и время вызова',
            'alias' => 'Дата вызова',
        ],
        'work_start_date' => [
            'name' => 'Дата и время начала работ',
            'alias' => 'Дата начала работ',
        ],
        'emergency_type' => [
            'name' => 'Тип ЧС',
            'alias' => '',
        ],
        'emergency_name' => [
            'name' => 'Наименование ЧС',
            'alias' => '',
        ],
        'minor_injury_count' => [
            'name' => 'Легких травм',
            'alias' => '',
        ],
        'severe_injury_count' => [
            'name' => 'Тяжелых травм',
            'alias' => '',
        ],
        'fatal_injury_count' => [
            'name' => 'Смертельных травм',
            'alias' => '',
        ],
        'evacuated_people_count' => [
            'name' => 'Эвакуировано',
            'alias' => '',
        ],
        'provided_medical_count' => [
            'name' => 'Оказано медицинской помощи',
            'alias' => 'Оказано мед. помощи',
        ],
        'client_equipment_count' => [
            'name' => 'Иная техника',
            'alias' => '',
        ],
        'third_parties_count' => [
            'name' => 'Количество сторонних организаций',
            'alias' => 'Сторонних организаций',
        ],
        'branch_worker_count' => [
            'name' => 'Задействовано работников филиала',
            'alias' => 'Работников филиала',
        ],
        'worker_man_hours' => [
            'name' => 'Количество человеко-часов',
            'alias' => 'Чел. часов',
        ],
        'minor_injury_total' => [
            'name' => 'Всего легких травм',
            'alias' => '',
        ],
        'severe_injury_total' => [
            'name' => 'Всего тяжелых травм',
            'alias' => '',
        ],
        'fatal_injury_total' => [
            'name' => 'Всего смертельных травм',
            'alias' => '',
        ],
        'evacuated_people_total' => [
            'name' => 'Всего эвакуированных',
            'alias' => '',
        ],
        'provided_medical_total' => [
            'name' => 'Всего оказано мед. помощи',
            'alias' => 'Всего мед. помощи',
        ],
        'client_equipment_total' => [
            'name' => 'Всего иной техники',
            'alias' => '',
        ],
        'third_parties_total' => [
            'name' => 'Всего сторонних организаций',
            'alias' => '',
        ],
        'branch_worker_total' => [
            'name' => 'Всего работников филиала',
            'alias' => '',
        ],
        'work_scope' => [
            'name' => 'Перечень и объем проведенных работ',
            'alias' => 'Объем работ',
        ],
        'situation_during_filing' => [
            'name' => 'Ситуация на момент подачи донесения',
            'alias' => 'Ситуация на момент подачи',
        ],
        'other_info' => [
            'name' => 'Дополнительная информация',
            'alias' => 'Доп. информация',
        ],
        'is_emergency_line' => [
            'name' => 'Тип оперативного вызова',
            'alias' => '',
        ],
        'close_date' => [
            'name' => 'Дата и время окончания работ',
            'alias' => 'Дата окончания работ',
        ],
        'is_closed' => [
            'name' => 'Ликвидирован',
            'alias' => 'Статус',
            'true' => 'Ликвидировано',
            'false' => 'В процессе',
        ],
        'created_at' => [
            'name' => 'Дата и время создания',
            'alias' => 'Дата создания',
        ],
        'updated_at' => [
            'name' => 'Дата и время обновления',
            'alias' => 'Дата обновления',
        ],
        'last_update_created_at' => [
            'name' => 'Дата создания последнего обновления',
            'alias' => 'Последнее обновление',
        ],
        'transport' => [
            'name' => 'Транспорт',
            'alias' => '',
        ],
        'transport_id' => [
            'name' => 'ID транспорта',
            'alias' => '',
        ],
        'transport_count' => [
            'name' => 'Количество транспорта',
            'alias' => 'Кол-во транспорта',
        ],
        'transport_totals' => [
            'name' => 'Всего транспорта',
            'alias' => '',
        ],
        'update_track' => [
            'name' => 'История обновлений',
            'alias' => 'Журнал',
        ],
        'status' => [
            'name' => 'Статус',
            'alias' => '',
        ],
        'fire_station' => [
            'name' => 'Пожарная часть',
            'alias' => '',
        ],
        'injured_workers' => [
            'name' => 'Пострадавшие оперативники',
            'alias' => '',
        ],
        'water_used' => [
            'name' => 'Использовано воды (литры)',
            'alias' => '',
        ],
        'foam_used' => [
            'name' => 'Использовано пены (литры)',
            'alias' => '',
        ],
        'distance_to_scene' => [
            'name' => 'Расстояние до места ЧС (км)',
            'alias' => '',
        ],
        'false_call' => [
            'name' => 'Ложный вызов',
            'alias' => '',
        ],

    ],

    'show' => [
        'not-found' => 'Отчет не найден.',
        'not-allowed' => 'Отчет не принадлежит вашему филиалу.',
    ],

    'create' => [
        'success' => 'Отчет успешно создан.',
        'branch-id-required' => 'Необходимо указать ID филиала.',
        'branch-id-required-admin' => 'Необходимо указать ID филиала для администраторов без филиала.',
    ],

    'update' => [
        'success' => 'Отчет успешно обновлен.',
        'not-allowed' => 'Вы не можете обновить этот отчет.',
        'closed' => 'Отчет закрыт, обновление невозможно.',
        'invalid-close-date' => 'Дата закрытия обновления не может быть раньше даты начала работ донесения.',
    ],

    'delete' => [
        'success' => 'Отчет успешно удален.',
        'not-found' => 'Отчет не найден.',
    ],

    'emergency-line' => [
        'true' => 'По линии ЧС',
        'false' => 'По договору',
    ]
];
