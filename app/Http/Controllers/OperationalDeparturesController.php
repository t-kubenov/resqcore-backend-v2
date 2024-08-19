<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationalDeparturesRequest;
use App\Http\Requests\UpdateOperationalDeparturesRequest;
use App\Models\OperationalDepartures;


class OperationalDeparturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }
        //             "type": "report",
        //             "attributes": {
        //                 "report_code": "10-4",
        //                 "caller": "Michael Honey",
        //                 "facility_name": null,
        //                 "emergency_location": "1234 Main St",
        //                 "call_date": "2024-08-10T06:54:09Z",
        //                 "emergency_name": "Fire", // Derive from energency type
        //                 "is_closed": false
        //             }
        //         }
        //     ]
        // }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperationalDeparturesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OperationalDepartures $operationalDepartures)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOperationalDeparturesRequest $request, OperationalDepartures $operationalDepartures)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperationalDepartures $operationalDepartures)
    {
        //
    }
}
