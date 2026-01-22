<?php

namespace App\Http\Controllers;

use App\Enums\Vehicle\TechnicalCondition;
use App\Http\Services\Vehicle\VehicleService;
use App\Models\Vehicle\Vehicle;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleStatisticsController extends Controller
{
    use Cacheable;

    private VehicleService $service;
    private Builder $query;
    private Request $request;

    public function __construct(VehicleService $service, Request $request)
    {
        $this->service = $service;
        $this->query = Vehicle::query();
        $this->request = $request;

        $service->filter($this->query, $request->all());
    }

    private function graphMap(): array
    {
        return [
            'graph-list' => 'graphList',
            'cards' => 'cards',
            'branch-office' => 'branchOffice',
            'vehicle-type' => 'vehicleType',
            'usage-type' => 'usageType',
            'action-type' => 'actionType',
            'owner' => 'owner',
            'user' => 'user',
            'insurance-expired' => 'insuranceExpired',
            'inspection-expired' => 'inspectionExpired',
            'rental-price-avg' => 'rentalPriceAvg'
        ];
    }

    public function show(string $graphName)
    {
        $map = $this->graphMap();

        if (!in_array($graphName, array_keys($map)))
        {
            throw new NotFoundHttpException;
        }

        $method = $map[$graphName];
        $roleId = auth()->user()->role_id;
        $branchId = auth()->user()->branch_id;

        $data = $this->cacheResponse("statistics:{$graphName}:r{$roleId}:b{$branchId}",
            $this->request,
            fn() => $this->$method());

        return $this->wrapData($data);
    }

    private function wrapData(Collection|array $data)
    {
        return response()->json(['data' => $data]);
    }

    public function graphList()
    {
        return array_keys($this->graphMap());
    }

    public function cards()
    {
        $filterQuery = $this->query->get([
            'technical_condition'
        ]);

        return [
            __('statistics.total') => $filterQuery->count(),
            __('etc.enum_options.Operational') => $filterQuery->where('technical_condition', TechnicalCondition::Operational)->count(),
            __('etc.enum_options.Faulty') => $filterQuery->where('technical_condition', TechnicalCondition::Faulty)->count(),
        ];
    }

    public function branchOffice()
    {
        $filterQuery = $this->query->selectRaw('branch_office_id, count(id)')->with('branch_office');

        return $filterQuery->groupBy('branch_office_id')
            ->orderBy('branch_office_id')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    $type->branch_office->name => $type->count
                ];
            });
    }

    public function vehicleType()
    {
        $filterQuery = $this->query->selectRaw('vehicle_type_id, count(id)')->with('vehicle_type');

        return $filterQuery->groupBy('vehicle_type_id')
            ->orderBy('vehicle_type_id')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    $type->vehicle_type->name => $type->count
                ];
            });
    }

    public function usageType()
    {
        $filterQuery = $this->query->selectRaw('usage_type, count(id)');

        return $filterQuery->groupBy('usage_type')
            ->orderBy('usage_type')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    __('etc.enum_options.'. $type->usage_type->name) => $type->count
                ];
            });
    }

    public function actionType()
    {
        $filterQuery = $this->query->selectRaw('action, count(id)');

        return $filterQuery->groupBy('action')
            ->orderBy('action')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    __('etc.enum_options.'. $type->action->name) => $type->count
                ];
            });
    }

    public function owner()
    {
        $filterQuery = $this->query->selectRaw('vehicle_owner_id, count(id)')->with('vehicle_owner');

        return $filterQuery->groupBy('vehicle_owner_id')
            ->orderBy('vehicle_owner_id')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    $type->vehicle_owner->name => $type->count
                ];
            });
    }

    public function user()
    {
        $filterQuery = $this->query->selectRaw('vehicle_user_id, count(id)')->with('vehicle_user');

        return $filterQuery->groupBy('vehicle_user_id')
            ->orderBy('vehicle_user_id')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    $type->vehicle_user->name => $type->count
                ];
            });
    }

    public function insuranceExpired()
    {
        $filterQuery = $this->query->where('insurance_expiration_date', '<', today())
            ->selectRaw('branch_office_id, count(id)')->with('branch_office');

        return $filterQuery->groupBy('branch_office_id')
            ->orderBy('branch_office_id')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    $type->branch_office->name => $type->count
                ];
            });
    }

    public function inspectionExpired()
    {
        $filterQuery = $this->query->where('inspection_expiration_date', '<', today())
            ->selectRaw('branch_office_id, count(id)')->with('branch_office');

        return $filterQuery->groupBy('branch_office_id')
            ->orderBy('branch_office_id')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    $type->branch_office->name => $type->count
                ];
            });
    }

    public function rentalPriceAvg()
    {
        $filterQuery = $this->query->whereNotNull('rental_price')
            ->selectRaw('branch_office_id, avg(rental_price)')->with('branch_office');

        return $filterQuery->groupBy('branch_office_id')
            ->orderBy('branch_office_id')
            ->get()
            ->mapWithKeys(function ($type) {
                return [
                    $type->branch_office->name => round($type->avg, 2)
                ];
            });
    }
}
