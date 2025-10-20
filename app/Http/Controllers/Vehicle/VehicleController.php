<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Http\Resources\Vehicle\VehicleCollection;
use App\Http\Resources\Vehicle\VehicleResource;
use App\Http\Services\Vehicle\VehicleService;
use App\Models\Vehicle\Vehicle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected VehicleService $vehicleService
    ){}

    public function index()
    {
        $collection = $this->vehicleService->index();

        return new VehicleCollection($collection);
    }

    public function store(StoreVehicleRequest $request)
    {
        $this->authorize('create', Vehicle::class);

        $vehicle = $this->vehicleService->store($request);

        return new VehicleResource($vehicle);
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorize('view');

        return new VehicleResource($vehicle);
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $vehicle = $this->vehicleService->update($request, $vehicle);

        return new VehicleResource($vehicle);
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return response()->json([
           "message" => __('documents.delete.success')
        ], 200);
    }
}
