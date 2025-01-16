<?php

namespace App\Http\Controllers\V2;

use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json([
            'companies' => Company::select('id', 'name')->get()
        ]);
    }
}
