<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\EmergencyType;
use App\Models\TransportType;

class AuxiliaryDataController extends Controller
{
    public function getBranchOffices(): JsonResponse
    {
        $branchOffices = BranchOffice::select('id', 'name')->get();

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
}
