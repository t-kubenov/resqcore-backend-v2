<?php

use App\Models\Image;
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

    $images = collect(range(1, 3))
        ->map(fn() => UploadedFile::fake()->image('vehicle.jpg', 640, 480))
        ->all();

    $vehicle = $this->vc->vehicleA;
    $this->ctx->centralUser->givePermissionTo('update vehicle');

    $this
        ->actingAs($this->ctx->centralUser)
        ->post(route('images.store', ['vehicles', $vehicle->id]), ['images' => $images])
        ->assertOk();

    $vehicle->refresh();

    $this->assertNotNull($vehicle->images);

    foreach ($vehicle->images as $image){
        Storage::disk()->assertExists($image->file_path);
    }
});

it('can\'t upload vehicle images without permission', function() {
    Storage::fake();

    $images = collect(range(1, 3))
        ->map(fn() => UploadedFile::fake()->image('vehicle.jpg', 640, 480))
        ->all();

    $vehicle = $this->vc->vehicleA;

    $this
        ->actingAs($this->ctx->centralUser)
        ->post(route('images.store', ['vehicles', $vehicle->id]), ['images' => $images])
        ->assertForbidden();

    $vehicle->refresh();

    $this->assertEmpty($vehicle->images);
});

it('deletes images', function () {
    Storage::fake('public');

    $vehicle = $this->vc->vehicleA;

    $this->ctx->centralUser->givePermissionTo('update vehicle');

    $image = Image::factory()->forModel($vehicle)->create();

    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('images.destroy', $image->id))
        ->assertNoContent();

    $this->assertEmpty($vehicle->images);

    Storage::disk('public')->assertMissing($image->filepath);
});

it('forbids deleting images without permission', function () {
    Storage::fake('public');

    $vehicle = $this->vc->vehicleA;

    $image = Image::factory()->forModel($vehicle)->create();

    $this
        ->actingAs($this->ctx->centralUser)
        ->deleteJson(route('images.destroy', $image->id))
        ->assertForbidden();

    $this->assertNotNull($vehicle->images);

    Storage::disk('public')->assertExists($image->filepath);
});
