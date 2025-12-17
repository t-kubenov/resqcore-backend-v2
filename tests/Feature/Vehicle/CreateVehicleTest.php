<?php

use Tests\Helpers\CompanyContext;
use Tests\Helpers\VehicleContext;

beforeEach(function() {
    $this->ctx = new CompanyContext();
    $this->vc = new VehicleContext($this->ctx);
    $this->vehiclePayload = $this->vc->makeVehicleCreatePayload();
});


it('creates a vehicle (central user)', function () {
    $this->ctx->centralUser->givePermissionTo('create vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->postJson(route('vehicles.store'), $this->vehiclePayload)
        ->assertCreated();
    $this
        ->assertDatabaseHas('vehicles', $this->vehiclePayload['data']['attributes']);
});

it('creates a vehicle (branch user)', function () {
    $this->ctx->branchUser->givePermissionTo('create vehicle');

    $this
        ->actingAs($this->ctx->branchUser)
        ->postJson(route('vehicles.store'), $this->vehiclePayload)
        ->assertCreated();
    $this
        ->assertDatabaseHas('vehicles', $this->vehiclePayload['data']['attributes']);
});

it('forbids central users without permission to create vehicles', function () {
    $this
        ->actingAs($this->ctx->centralUser)
        ->postJson(route('vehicles.store'), $this->vehiclePayload)
        ->assertForbidden();
});

it('forbids branch users without permission to create vehicles', function () {
    $this
        ->actingAs($this->ctx->branchUser)
        ->postJson(route('vehicles.store'), $this->vehiclePayload)
        ->assertForbidden();
});
