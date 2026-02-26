<?php

namespace App\Vehicle;

use App\Activity\ActivityCollection;
use Illuminate\Routing\Controller;
use App\Document\DocumentFormService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        $activities = $vehicle->activities->reverse()->values();

        $images = $vehicle->image_links;

        return VehicleResource::make($vehicle)->additional([
            ...($alerts ? ['alerts' => $alerts] : []),
            ...($activities ? ['activities' => ActivityCollection::make($activities)] : []),
            ...($images ? ['images' => $images]: [])

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

    public function export(FilterVehicleRequest $request)
    {
        return $this->index($request);
    }
}
