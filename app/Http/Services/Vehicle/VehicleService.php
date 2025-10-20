<?php

namespace App\Http\Services\Vehicle;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Http\Services\GeneralService;
use App\Models\Vehicle\Vehicle;

class VehicleService extends GeneralService
{
    public function index()
    {
        $query = Vehicle::query()
            ->orderBy('created_at', 'desc');

        return $this->filterByRoles($query)->paginate(15);
    }

    public function store(StoreVehicleRequest $request): Vehicle
    {
        $this->throttle($request);
        return $this->create($request);
    }

    private function create(StoreVehicleRequest $request): Vehicle
    {
        $data = $request->validated()['data']['attributes'];

        $vehicle = Vehicle::create($data);

        return $vehicle;
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): Vehicle
    {
        $data = $request->validated()['data']['attributes'];

        $vehicle->update($data);

        return $vehicle;
    }
}
