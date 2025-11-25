<?php

use Tests\Helpers\CompanyContext;
use Tests\Helpers\VehicleContext;

beforeEach(function() {
    $this->ctx = new CompanyContext();
    $this->vc = new VehicleContext($this->ctx);
    $this->vehiclePayload = $this->vc->makeVehicleCreatePayload();
});

it('lets central office users delete vehicles', function (){
    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleA->id))
        ->assertOk();
});

it('forbids central office users from deleting vehicles in other companies', function (){
    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('vehicles.destroy', $this->vc->vehicleA->id))
        ->assertOk();
});
