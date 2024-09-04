<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use App\Models\BranchOffice;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuxiliaryDataController extends Controller
{
    public function getAuxiliaryData(): JsonResponse
    {
        $branchOffices = BranchOffice::all();
        $emergencyTypes = Report::getEmergencyTypes();
        $transportTypes = Transport::getTransportTypes();


        $data = [];

        $data[] = [
            'branchOffices' => $branchOffices->pluck('name', 'id')
        ];

        $data[] = [
            'emergencyTypes' => $emergencyTypes
        ];

        $data[] = [
            'transportTypes' => $transportTypes
        ];

        return response()->json(['data' => $data], 200);
    }
}
