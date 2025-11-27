<?php

use Illuminate\Http\UploadedFile;
use Tests\Helpers\CompanyContext;
use Tests\Helpers\VehicleContext;

beforeEach(function() {
    $this->ctx = new CompanyContext();
    $this->vc = new VehicleContext($this->ctx);
    $this->vehiclePayload = $this->vc->makeVehicleCreatePayload();
});


it('creates a vehicle', function () {
    $this
        ->actingAs($this->ctx->centralUser)
        ->postJson(route('vehicles.store'), $this->vehiclePayload)
        ->assertCreated();
    $this
        ->assertDatabaseHas('vehicles', $this->vehiclePayload['data']['attributes']);
});

it('forbids branch users to create vehicles', function () {
    $this
        ->actingAs($this->ctx->branchUser)
        ->postJson(route('vehicles.store'), $this->vehiclePayload)
        ->assertStatus(403);
});

it('uploads vehicle images', function() {
    Storage::fake();
    $file = UploadedFile::fake()->image('vehicle.jpg', 640, 480);
    $vehicle = $this->vc->vehicleA;

    $this
        ->actingAs($this->ctx->centralUser)
        ->post(route('vehicles.upload-image', $vehicle->id), ['image' => $file])
        ->assertOk();

    $vehicle->refresh();

    $this->assertNotNull($vehicle->image_path);
    Storage::disk()->assertExists($this->vc->vehicleA->image_path);
});
