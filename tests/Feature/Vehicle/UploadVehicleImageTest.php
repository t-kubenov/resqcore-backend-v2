<?php

use Illuminate\Http\UploadedFile;
use Tests\Helpers\CompanyContext;
use Tests\Helpers\VehicleContext;


beforeEach(function() {
    $this->ctx = new CompanyContext();
    $this->vc = new VehicleContext($this->ctx);
    $this->vehiclePayload = $this->vc->makeVehicleCreatePayload();
});


it('uploads vehicle images', function() {
    Storage::fake();
    $file = UploadedFile::fake()->image('vehicle.jpg', 640, 480);
    $vehicle = $this->vc->vehicleA;
    $this->ctx->centralUser->givePermissionTo('update vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->post(route('vehicles.upload-image', $vehicle->id), ['image' => $file])
        ->assertOk();

    $vehicle->refresh();

    $this->assertNotNull($vehicle->image_path);
    Storage::disk()->assertExists($this->vc->vehicleA->image_path);
});

it('can\'t upload vehicle images without permission', function() {
    Storage::fake();
    $file = UploadedFile::fake()->image('vehicle.jpg', 640, 480);
    $vehicle = $this->vc->vehicleA;
    $this->ctx->centralUser->givePermissionTo('update vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->post(route('vehicles.upload-image', $vehicle->id), ['image' => $file])
        ->assertOk();

    $vehicle->refresh();

    $this->assertNotNull($vehicle->image_path);
    Storage::disk()->assertExists($this->vc->vehicleA->image_path);
});
