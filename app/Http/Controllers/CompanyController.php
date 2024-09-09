<?php

namespace App\Http\Controllers;

use App\Models\Company;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json([
            'companies' => Company::select('id', 'name')->get()
        ]);
    }
}
