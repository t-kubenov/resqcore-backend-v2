<?php

use App\Models\Vehicle\Vehicle;
use Tests\Helpers\CompanyContext;
use Tests\Helpers\VehicleContext;


beforeEach(function() {
    $this->ctx = new CompanyContext();
    $this->ctxB = new CompanyContext();
    $this->vc = new VehicleContext($this->ctx);
    $this->vcB = new VehicleContext($this->ctxB);
});


it('shows a list of company vehicles to central office users', function() {
    $user = $this->ctx->centralUser;

    $total = Vehicle::whereHas('branch_office', function($q) use($user){
        $q->where('rescue_company_id', $user['rescue_company_id']);
    })->count();

    $this->actingAs($user)
        ->getJson(route('vehicles.index'))
        ->assertOk()
        ->assertJsonStructure($this->vc->indexStructure)
        ->assertJsonFragment(['total' => $total]);
});

it('shows a vehicle to a central office user', function() {
    $this->actingAs($this->ctx->centralUser)
        ->getJson(route('vehicles.show', $this->vc->vehicleA['id']))
        ->assertOk()
        ->assertJsonStructure($this->vc->showStructure);
});

it('shows a list of branch vehicles to branch office users', function() {
    $total = Vehicle::where('branch_office_id', $this->ctx->branchA->id)->count();

    $this->actingAs($this->ctx->branchUser)
        ->getJson(route('vehicles.index'))
        ->assertOk()
        ->assertJsonStructure($this->vc->indexStructure)
        ->assertJsonFragment(['total' => $total]);
});

it('shows a vehicle to a branch office user', function() {
    $this->actingAs($this->ctx->branchUser)
        ->getJson(route('vehicles.show', $this->vc->vehicleA['id']))
        ->assertOk()
        ->assertJsonStructure($this->vc->showStructure);
});

it('forbids branch users to view other branches\' vehicles', function (){
    $this->actingAs($this->ctx->branchUser)
        ->getJson(route('vehicles.show', $this->vc->vehicleB['id']))
        ->assertForbidden();
});

it('forbids central users to view other companies\' vehicles', function (){
    $this->actingAs($this->ctx->centralUser)
        ->getJson(route('vehicles.show', $this->vcB->vehicleA['id']))
        ->assertForbidden();
});
