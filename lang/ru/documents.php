<?php

return [
    'types' => [
        'App\Models\Report\Report'          => 'донесение',
        'App\Models\Survey\Survey'          => 'проф. выезд',
        'App\Models\Survey\Violation'       => 'нарушение',
        'App\Models\Vehicle\Vehicle'        => 'транспорт',
    ],

    'show' => [
        'not-found' => 'Запись не найдена.',
        'not-allowed' => 'Запись не принадлежит вашему филиалу.',
    ],

    'create' => [
        'success' => 'Запись успешно создана.',
        'branch-id-required' => 'Необходимо указать ID филиала.',
        'branch-id-required-admin' => 'Необходимо указать ID филиала для администраторов без филиала.',
    ],

    'update' => [
        'success' => 'Запись успешно обновлена.',
        'not-allowed' => 'Вы не можете обновить эту запись.',
        'closed' => 'Запись закрыта, обновление невозможно.',
        'invalid-close-date' => 'Дата закрытия обновления не может быть раньше даты начала работ донесения.',
    ],

    'delete' => [
        'success' => 'Запись успешно удалена.',
        'not-found' => 'Запись не найдена.',
    ],

    'image' => [
        'upload_success' => 'Изображение успешно загружено',
        'upload_failure' => 'Не удалось загрузить изображение',
        'model_not_found' => 'Неверный тип документа'
    ],

    'policy' => [
        'access-denied' => 'У вашей учетной записи нет доступа для просмотра данного модуля.'
    ]
];
