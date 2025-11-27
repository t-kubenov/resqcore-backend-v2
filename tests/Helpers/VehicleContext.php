<?php

namespace Tests\Helpers;

use App\Models\Vehicle\Vehicle;

final class VehicleContext
{
    public Vehicle $vehicleA;
    public Vehicle $vehicleB;
    public array $indexStructure;
    public array $showStructure;

    public function __construct(protected CompanyContext $ctx)
    {
        $this->vehicleA = Vehicle::factory()->for($ctx->branchA, 'branch_office')->create();
        $this->vehicleB = Vehicle::factory()->for($ctx->branchB, 'branch_office')->create();

        $this->indexStructure = [
            'columns' => [
                '*' => [
                    'key',
                    'name',
                ]
            ],
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        // etc.
                    ]
                ]
            ],
        ];

        $this->showStructure = [
            'columns' => [
                '*' => [
                    'key',
                    'name',
                ]
            ],
            'data' => [
                'id',
                'type',
                'attributes' => [
                    // etc.
                ]
            ]
        ];
    }

    public static function makeVehicleCreatePayload(): array
    {
        $vehicle = Vehicle::factory()->forTests()->raw();

        return [
            'data' => [
                'type' => Vehicle::class,
                'attributes' => $vehicle,
            ]
        ];
    }

}
