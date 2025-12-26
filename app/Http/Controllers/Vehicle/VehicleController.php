<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicle\FilterVehicleRequest;
use App\Http\Requests\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Http\Resources\Activity\ActivityCollection;
use App\Http\Resources\Vehicle\VehicleCollection;
use App\Http\Resources\Vehicle\VehicleResource;
use App\Http\Services\DocumentFormService;
use App\Http\Services\ImageUploadService;
use App\Http\Services\Vehicle\VehicleService;
use App\Http\Services\Vehicle\VehicleAlertService;
use App\Models\Vehicle\Vehicle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected VehicleService $vehicleService
    ){}

    public function index(FilterVehicleRequest $request)
    {
        $this->authorize('view-any', Vehicle::class);

        $collection = $this->vehicleService->index($request);

        return new VehicleCollection($collection);
    }

    public function create(DocumentFormService $service)
    {
        $this->authorize('create', Vehicle::class);

        $fields = $service->getCategorizedCreateFields(Vehicle::class);

        return response()->json([
            'categories' => $fields
        ]);
    }

    public function store(StoreVehicleRequest $request)
    {
        $this->authorize('create', Vehicle::class);

        $vehicle = $this->vehicleService->store($request);

        return new VehicleResource($vehicle);
    }

    public function show(Vehicle $vehicle, VehicleAlertService $alertService)
    {
        $this->authorize('view', $vehicle);

        $alerts = $alertService->getAlerts($vehicle);

        $activities = $vehicle->activities;

        return VehicleResource::make($vehicle)->additional([
            ...($alerts ? ['alerts' => $alerts] : []),
            ...($activities ? ['activities' => ActivityCollection::make($activities)] : []),
            // todo: move this to the Resource once I write a parent resource for all documents.
            //       The controller should decide what resource to show,
            //       the resource decides what to show.
        ]);
    }

    public function edit(Vehicle $vehicle, DocumentFormService $service)
    {
        $this->authorize('update', $vehicle);

        return response()->json([
            'categories' => $service->getCategorizedCreateFields(Vehicle::class),
            'data' => $vehicle,
        ]);
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

    public function filter()
    {
        return response()->json(['data' => Vehicle::filters()]);
    }

    public function uploadImage(Request $request, Vehicle $vehicle, ImageUploadService $service)
    {
        $this->authorize('update', $vehicle);

        $request->validate([
            "image" => "required|image|mimes:jpeg,png,jpg,svg,webp|max:2048"
        ]);

        $success = $service->uploadAndReplace(
            $vehicle,
            $request->file('image'),
            'vehicles',
            'image_path',
        );

        return ["message" => $success
            ? __("documents.image.upload_success")
            : __("documents.image.upload_failure")];
    }

    public function export(FilterVehicleRequest $request)
    {
        return $this->index($request);
    }
}
