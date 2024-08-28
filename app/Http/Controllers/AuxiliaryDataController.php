<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use App\Models\BranchOffice;
use App\Models\Operation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuxiliaryDataController extends Controller
{
    public function getAuxiliaryData(): JsonResponse
    {
        $branchOffices = BranchOffice::all();
        $emergencyTypes = Operation::getEmergencyTypes();
        $transportTypes = Transport::getTransportTypes();


        $data = [];

        $data[] = [
            'branchOffices' => $branchOffices->pluck('name', 'id')
        ];

        $data[] = [
            'operationTypes' => $emergencyTypes
        ];

        $data[] = [
            'transportTypes' => $transportTypes
        ];

        return response()->json(['data' => $data], 200);
    }
}
