<?php

namespace App\Http\Controllers\V2;

use App\Models\BranchOffice;
use App\Models\Egov\MineralField;
use App\Models\Egov\OilField;
use App\Models\Egov\SubsoilContract;
use App\Models\EmergencyType;
use App\Models\TransportType;
use Illuminate\Http\JsonResponse;

class AuxiliaryDataController extends Controller
{
    public function getBranchOffices(): JsonResponse
    {
        $branchOffices = BranchOffice::all();

        return response()->json(['data' => $branchOffices], 200);
    }

    public function getEmergencyTypes(): JsonResponse
    {
        $emergencyTypes = EmergencyType::all();

        return response()->json(['data' => $emergencyTypes], 200);
    }

    public function getTransportTypes(): JsonResponse
    {
        $transportTypes = TransportType::all();

        return response()->json(['data' => $transportTypes], 200);
    }

    public function getMineralFields(): JsonResponse
    {
        $mineralFields = MineralField::orderBy('id')->paginate(15);

        return response()->json(['data' => $mineralFields], 200);
    }

    public function getOilFields(): JsonResponse
    {
        $oilFields = OilField::orderBy('id')->paginate(15);

        return response()->json(['data' => $oilFields], 200);
    }

    public function getSubsoilContracts(): JsonResponse
    {
        $subsoilContracts = SubsoilContract::orderBy('id')->paginate(15);

        return response()->json(['data' => $subsoilContracts], 200);
    }
}
