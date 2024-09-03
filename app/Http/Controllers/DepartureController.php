<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Operation;
use App\Http\Resources\DepartureResource;
use App\Http\Resources\DepartureCollection;

class DepartureController extends Controller
{
    public function index()
    {
        return new DepartureCollection(Operation::paginate());
    }

    public function show($id)
    {
        $departure = Operation::find($id);
        return new DepartureResource($departure);
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
