<?php

return [
    'fields' => [
        'id' => 'ID',
        'branch_office_id' => 'Филиал',
        'brand' => 'Марка автомашины',
        'vehicle_type_id' => 'Тип',
        'plate_number' => 'Гос. номер',
        'image' => 'Изображение',
        'year' => 'Год выпуска',
        'engine_size' => 'Объем двигателя',
        'fuel_type' => 'Тип топлива',
        'gearbox' => 'КПП',
        'drive' => 'Тип привода',
        'location' => 'Место дислокации / обслуживаемый объект',
        'vehicle_owner_id' => 'Владелец',
        'usage_type' => 'Вид пользования',
        'vehicle_user_id' => 'Организация-эксплуатант',
        'rental_contract' => 'Договор аренды',
        'rental_price' => 'Стоимость в месяц',
        'technical_condition' => 'Тех. состояние',
        'action' => 'Действия',
        'notes' => 'Уточнения',
        'insurance_expiration_date' => 'Дата окончания страхового полиса',
        'inspection_expiration_date' => 'Дата окончания тех. осмотра',
        'inspection_date' => 'Дата прохождения тех. осмотра',
    ],
    'alerts' => [
        'expiration' => [
            'expired' => ':field истекла.',
            'expires_in' => 'Через :count день истекает :field|Через :count дня истекает :field|Через :count дней истекает :field',
        ],
    ],
];
