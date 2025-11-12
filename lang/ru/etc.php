<?php

return [
    'email' => [
        'send' => [
            'success' => 'Сообщение отправлено успешно.',
            'error' => 'Произошла ошибка. Сообщение не было отправлено.',
        ],
    ],
    'boolean' => [
        'true' => 'Да',
        'false' => 'Нет',
    ],
    'enum_options' => [
        // ActionType
        'None' => 'Пустые',
        'ForSale' => 'На реализацию',
        'Transfer' => 'Перемещение',
        'Acquired' => 'Приобретен',
        'Reserved' => 'Резерв',

        // DriveType
        'RearWheelDrive' => 'Задний привод',
        'FrontWheelDrive' => 'Передний привод',
        'AllWheelDrive' => 'Полный привод',
        'FourWheelDrive' => 'Полный привод 4x4',

        // FuelType
        'Diesel' => 'Дизель',
        'Gasoline' => 'Бензин',
        'EV' => 'Электродвигатель',

        // GearboxType
        'Manual' => 'Механическая',
        'Automatic' => 'Автоматическая',

        // TechnicalCondition
        'Operational' => 'Исправен',
        'Faulty' => 'Неисправен',

        // UsageType
        'Owned' => 'Собственность',
        'Rental' => 'Аренда',
        'CustomerRental' => 'Аренда у заказчика',
        'CustomerOwned' => 'Собственность заказчика',
        'TransferredOwnership' => 'Передана в аренду',
    ],
];
