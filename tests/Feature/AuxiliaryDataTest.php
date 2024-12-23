<?php


use function Tests\Helpers\{loadUsers, assertEndpoint};


$auxiliaryDataRoutes = [
    'transport-types',
    'emergency-types',
    'branch-offices',
    'mineral-fields',
    'oil-fields',
    'subsoil-contracts',
];


beforeEach(function (){
    $this->users = loadUsers();
});


test('transport types', function () {
    $data = [
        'data' => [
            '*' => [
                'id',
                'key',
                'name',
            ]
        ]
    ];

    assertEndpoint($this, 'auxiliary/transport-types', $data);
});


test('emergency types', function () {
    $data = [
        'data' => [
            '*' => [
                'id',
                'key',
                'name',
            ]
        ]
    ];

    assertEndpoint($this, 'auxiliary/emergency-types', $data);
});


test('branch offices', function () {
    $data = [
        'data' => [
            '*' => [
                'id',
                'key',
                'name',
                'alias',
            ]
        ]
    ];

    assertEndpoint($this, 'auxiliary/branch-offices', $data);
});
