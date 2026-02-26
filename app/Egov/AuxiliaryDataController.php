<?php

namespace App\Egov;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class AuxiliaryDataController extends Controller
{
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
