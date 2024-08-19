<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Models\Operation;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function reports()
    {
        // // Get sorting parameters
        // $sortParam = $request->query('sort', 'created_at'); // Default sort by 'created_at'
        // $sortOrder = 'asc';

        // // Check if the sort parameter has a leading hyphen
        // if (strpos($sortParam, '-') === 0) {
        //     $sortOrder = 'desc';
        //     $sortParam = ltrim($sortParam, '-');
        // }

        // // Get pagination parameters
        // $page = $request->query('page', 1); // Default page is 1
        // $perPage = 15; // Hardcoded items per page


        // try {
        //     // Fetch reports with sorting and pagination
        //     $reports = Operation::orderBy($sortParam, $sortOrder)->paginate($perPage, ['*'], 'page', $page);
        // }
        // catch (Exception $e) {
        //     return response()->json(['error' => 'An error occurred while fetching reports.'], 500);
        // }



        // $reports = Operation::all();

        $data = [];
        foreach ($operationalReports as $departure) {
            $data[] = [
                'id' => $departure->id,
                'type' => 'report',
                'attributes' => [
                    'report_code' => $departure->report_code,
                    'caller' => $departure->caller,
                    'facility_name' => $departure->facility->location?? $departure->line_facility_location,
                    'call_date' => $departure->call_date->toIso8601String(),
                    'emergency_name' => $departure->emergency_type, // Derive from energency type
                    'is_closed' => $departure->is_closed,
                ],
            ];
        }

        return response()->json(['data' => $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Operation $operation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOperationRequest $request, Operation $operation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        //
    }
}
