<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Report;
use App\Http\Resources\DepartureResource;
use App\Http\Resources\DepartureCollection;
use App\Services\DepartureService;


class DepartureController extends Controller
{
    protected $departureService;

    public function __construct(DepartureService $departureService)
    {
        $this->departureService = $departureService;
    }

    public function index(Request $request)
    {
        return new DepartureCollection($this->departureService->getDepartures($request));
    }

    public function countTable(Request $request)
    {
        return response()->json($this->departureService->getCountTable($request));
    }

    public function show($id)
    {
        //
    }

    public function store(){
        //
    }

    public function update(){
        //
    }

    public function destroy(){
        //
    }

}
