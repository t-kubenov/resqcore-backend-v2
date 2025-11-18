<?php

namespace App\Http\Services\Vehicle;

use App\Http\Requests\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Http\Services\GeneralService;
use App\Models\Vehicle\Vehicle;
use App\Services\Filters\Configurations\VehicleFilterConfig;
use Illuminate\Http\Request;

class VehicleService extends GeneralService
{
    public function __construct(
        protected VehicleFilterConfig $filterConfig
    ){}

    public function index(Request $request)
    {
        $query = Vehicle::query()
            ->orderBy('updated_at', 'desc');

        $this->filterByRoles($query);

        $this->filterConfig->apply($query, $request->all());

        return $query->paginate(15);
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
