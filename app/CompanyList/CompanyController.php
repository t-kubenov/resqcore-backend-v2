<?php

namespace App\CompanyList;

use Illuminate\Routing\Controller;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json([
            'companies' => Company::select('id', 'name')->get()
        ]);
    }
}
