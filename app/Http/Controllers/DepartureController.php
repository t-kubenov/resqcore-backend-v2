<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
