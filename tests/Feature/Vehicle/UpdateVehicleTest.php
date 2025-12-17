<?php

use Tests\Helpers\CompanyContext;
use Tests\Helpers\VehicleContext;

beforeEach(function() {
    $this->ctx = new CompanyContext();
    $this->vc = new VehicleContext($this->ctx);
    $this->ctxB = new CompanyContext();
    $this->vcB = new VehicleContext($this->ctxB);
    $this->vehiclePayload = $this->vc->makeVehicleCreatePayload();
});

it('lets central users update vehicles', function (){
    $this->ctx->centralUser->givePermissionTo('update vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->putJson(route('vehicles.update', $this->vc->vehicleA->id), $this->vehiclePayload)
        ->assertOk();
    $this
        ->assertDatabaseHas('vehicles', array_merge([
            'id' => $this->vc->vehicleA->id,
        ], $this->vehiclePayload['data']['attributes']));
});

it('lets branch users update vehicles', function (){
    $this->ctx->branchUser->givePermissionTo('update vehicle');

    $this
        ->actingAs($this->ctx->branchUser)
        ->putJson(route('vehicles.update', $this->vc->vehicleA->id), $this->vehiclePayload)
        ->assertOk();
    $this
        ->assertDatabaseHas('vehicles', array_merge([
            'id' => $this->vc->vehicleA->id,
        ], $this->vehiclePayload['data']['attributes']));
});

it('forbids central users without permission from updating vehicles', function (){
    $this
        ->actingAs($this->ctx->centralUser)
        ->putJson(route('vehicles.update', $this->vc->vehicleA->id), $this->vehiclePayload)
        ->assertForbidden();
});

it('forbids branch users without permission from updating vehicles', function (){
    $this
        ->actingAs($this->ctx->branchUser)
        ->putJson(route('vehicles.update', $this->vc->vehicleA->id), $this->vehiclePayload)
        ->assertForbidden();
});

it('forbids central users from updating vehicles in other companies', function (){
    $this->ctx->centralUser->givePermissionTo('update vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->putJson(route('vehicles.update', $this->vcB->vehicleA->id), $this->vehiclePayload)
        ->assertForbidden();
});

it('forbids branch users from updating vehicles in other branches', function (){
    $this->ctx->branchUser->givePermissionTo('update vehicle');

    $this
        ->actingAs($this->ctx->branchUser)
        ->putJson(route('vehicles.update', $this->vcB->vehicleA->id), $this->vehiclePayload)
        ->assertForbidden();
});
