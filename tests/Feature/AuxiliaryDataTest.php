<?php

use function Tests\Helpers\{getRandomCentralAndBranchUsers, assertUsersCanAccessEndpoint};

// the data should be in the context of users, branch offices and the company.

beforeEach(function (){
    $this->users = getRandomCentralAndBranchUsers();
});


test('data format', function () {
    $auxiliaryDataRoutes = [
        'branch_office',
        'emergency_type',
        'transport_type',
        'departure_type',
        'legal_entity',
        'legal_entity_unit',
        'created_by',
    ];

    $data = [
        'data' => [
            '*' => [
                'id',
                'name',
            ]
        ]
    ];

    foreach ($auxiliaryDataRoutes as $route){
        assertUsersCanAccessEndpoint($this, route('auxiliary.field-options', $route), $data);
    }
});
