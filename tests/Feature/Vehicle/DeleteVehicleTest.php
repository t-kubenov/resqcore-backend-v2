<?php

use Tests\Helpers\CompanyContext;
use Tests\Helpers\VehicleContext;

beforeEach(function() {
    $this->ctx = new CompanyContext();
    $this->vc = new VehicleContext($this->ctx);
    $this->vehiclePayload = $this->vc->makeVehicleCreatePayload();
});

it('lets central office users delete vehicles', function (){
    $this->ctx->centralUser->givePermissionTo('delete vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleA->id))
        ->assertOk();
});

it('lets branch office users delete vehicles', function (){
    $this->ctx->branchUser->givePermissionTo('delete vehicle');

    $this
        ->actingAs($this->ctx->branchUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleA->id))
        ->assertOk();
});

it('forbids central office users from deleting vehicles in other companies', function (){
    $this->ctx->branchUser->givePermissionTo('delete vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleA->id))
        ->assertForbidden();
});

it('forbids branch office users from deleting vehicles in other branches', function (){
    $this->ctx->branchUser->givePermissionTo('delete vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleB->id))
        ->assertForbidden();
});

it('forbids central office users from deleting vehicles without permission', function (){
    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleA->id))
        ->assertForbidden();
});

it('forbids branch office users from deleting vehicles without permission', function (){
    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleA->id))
        ->assertForbidden();
});

