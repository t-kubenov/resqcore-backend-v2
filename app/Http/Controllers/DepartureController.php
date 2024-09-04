<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Report;
use App\Http\Resources\DepartureResource;
use App\Http\Resources\DepartureCollection;

class DepartureController extends Controller
{
    public function index()
    {
        return new DepartureCollection(Report::paginate());
    }

    public function show($id)
    {
        $departure = Report::find($id);
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
