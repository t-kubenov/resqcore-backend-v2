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
    public function getAuxiliaryData(): JsonResponse
    {
        $branchOffices = BranchOffice::all();
        $emergencyTypes = EmergencyType::all();
        $transportTypes = TransportType::all();


        $data = [];

        $data[] = [
            'branchOffices' => $branchOffices->pluck('name', 'id')
        ];

        $data[] = [
            'emergencyTypes' => $emergencyTypes->pluck('name', 'id')
        ];

        $data[] = [
            'transportTypes' => $transportTypes->pluck('name', 'id')
        ];

        return response()->json(['data' => $data], 200);
    }
}
