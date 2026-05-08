<?php

return [

    [
        'label' => 'Опер. выезды',
        'icon' => 'Assignment',
        'can' => null,
        'children' => [
            [
                'label' => 'Выезды',
                'route' => '/Departures',
                'can' => null,
            ],
            [
                'label' => 'Количество выездов',
                'route' => '/QuantityPage',
                'can' => ['central_office_user', 'admin'],
            ],
            [
                'label' => 'Статистика',
                'route' => '/StatisticPage',
                'can' => null,
            ],
        ]
    ],
    [
        'label' => 'Проф. обследования',
        'icon' => 'LocalPolice',
        'can' => null,
        'children' => [
            [
                'label' => 'Проф. выезды',
                'route' => '/Preventive',
                'can' => null,
            ],
        ]
    ],
    [
        'label' => 'Транспорт',
        'icon' => 'AirportShuttle',
        'can' => null,
        'children' => [
            [
                'label' => 'Транспорт',
                'route' => '/Vehicles',
                'can' => null,
            ],
            [
                'label' => 'Статистика',
                'route' => '/TransportStatistic',
                'can' => null,
            ],
        ]
    ],

//    "Опер. выезды" => [
//        "Выезды" => "/Departures",
//        "Количество выездов" => "/QuantityPage",
//        "Статистика" => "/StatisticPage",
//    ],
//    "Проф. обследования" => [
//        "Проф. выезды" => "/Preventive",
//    ],
//    "Транспорт" => [
//        "Транспорт" => "/Vehicles",
//        "Статистика" => "/TransportStatistic",
//    ],
];
