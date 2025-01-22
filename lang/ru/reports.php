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
            'alias' => '',
        ],
        'facility_id' => [
            'name' => 'ID объекта',
            'alias' => '',
        ],
        'facility_name' => [
            'name' => 'Наименование объекта',
            'alias' => 'Объект',
        ],
        'facility_owner_id' => [
            'name' => 'ID владельца объекта',
            'alias' => '',
        ],
        'facility_owner' => [
            'name' => 'Владелец объекта',
            'alias' => 'Владелец',
        ],
        'reported_by' => [
            'name' => 'Сообщил',
            'alias' => '',
        ],
        'report_code' => [
            'name' => 'Код отчета',
            'alias' => 'Код',
        ],
        'caller' => [
            'name' => 'Кто произвел вызов',
            'alias' => 'Заявитель',
        ],
        'line_facility_location' => [
            'name' => 'Местоположение объекта',
            'alias' => 'Место объекта',
        ],
        'line_facility_owner' => [
            'name' => 'Владелец объекта',
            'alias' => 'Владелец объекта',
        ],
        'emergency_location' => [
            'name' => 'Место ЧС',
            'alias' => '',
        ],
        'emergency_info' => [
            'name' => 'Информация о ЧС',
            'alias' => 'Инфо ЧС',
        ],
        'call_date' => [
            'name' => 'Дата и время вызова',
            'alias' => 'Дата вызова',
        ],
        'work_start_date' => [
            'name' => 'Дата и время начала работы',
            'alias' => 'Начало работы',
        ],
        'emergency_type' => [
            'name' => 'Тип ЧС',
            'alias' => '',
        ],
        'minor_injury_count' => [
            'name' => 'Легкие травмы',
            'alias' => 'Легк. травмвы',
        ],
        'severe_injury_count' => [
            'name' => 'Тяжелые травмы',
            'alias' => 'Тяж. травмы',
        ],
        'fatal_injury_count' => [
            'name' => 'Смертельные травмы',
            'alias' => 'Смерт. травмы',
        ],
        'evacuated_people_count' => [
            'name' => 'Эвакуировано',
            'alias' => '',
        ],
        'provided_medical_count' => [
            'name' => 'Оказана медецинская помощь',
            'alias' => 'Мед. помощь',
        ],
        'client_equipment_count' => [
            'name' => 'Иная техника',
            'alias' => '',
        ],
        'third_parties_count' => [
            'name' => 'Количество сторонних организаций',
            'alias' => 'Сторон. орг.',
        ],
        'branch_worker_count' => [
            'name' => 'Количество работников филиала',
            'alias' => 'Работники',
        ],
        'worker_man_hours' => [
            'name' => 'Человеко-часы',
            'alias' => '',
        ],
        'minor_injury_total' => [
            'name' => 'Общее количество легких травм',
            'alias' => 'Легкие травмы',
        ],
        'severe_injury_total' => [
            'name' => 'Общее количество тяжелых травм',
            'alias' => 'Тяж. травмы',
        ],
        'fatal_injury_total' => [
            'name' => 'Общее количество смертельных травм',
            'alias' => 'Смерт. травмы',
        ],
        'evacuated_people_total' => [
            'name' => 'Общее количество эвакуированных',
            'alias' => 'Эвак. всего',
        ],
        'provided_medical_total' => [
            'name' => 'Общее количество оказанных медицинских помощей',
            'alias' => 'Мед. всего',
        ],
        'client_equipment_total' => [
            'name' => 'Общее количество оборудования клиента',
            'alias' => 'Оборуд. всего',
        ],
        'third_parties_total' => [
            'name' => 'Общее количество третьих лиц',
            'alias' => 'Сторон. всего',
        ],
        'work_scope' => [
            'name' => 'Объем работ',
            'alias' => '',
        ],
        'situation_during_filing' => [
            'name' => 'Ситуация на момент подачи',
            'alias' => 'Ситуация',
        ],
        'other_info' => [
            'name' => 'Дополнительная информация',
            'alias' => 'Доп. инфо',
        ],
        'is_emergency_line' => [
            'name' => 'По линии ЧС',
            'alias' => '',
        ],
        'close_date' => [
            'name' => 'Дата закрытия',
            'alias' => 'Закрытие',
        ],
        'is_closed' => [
            'name' => 'Закрыт',
            'alias' => '',
        ],
        'created_at' => [
            'name' => 'Дата создания',
            'alias' => '',
        ],
        'updated_at' => [
            'name' => 'Дата обновления',
            'alias' => '',
        ],
        'last_update_created_at' => [
            'name' => 'Дата создания последнего обновления',
            'alias' => 'Посл. обнов. создано',
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
        'update_track' => [
            'name' => 'История обновлений',
            'alias' => 'История',
        ],
        'status' => [
            'name' => 'Статус',
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
        'invalid-close-date' => 'Дата закрытия обновления не может быть раньше даты начала работы донесения.',
    ],

    'delete' => [
        'success' => 'Отчет успешно удален.',
        'not-found' => 'Отчет не найден.',
    ],
];
