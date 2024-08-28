<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UpdateUpdateRequest;
use App\Models\Update;

class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Update $Update)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUpdateRequest $request, Update $Update)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Update $Update)
    {
        //
    }
}
