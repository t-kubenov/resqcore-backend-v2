<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->has('path')) {
            return response()->json(['message' => 'File path is required'], 400);
        }

        $filePath = public_path('storage/' . $request->query('path'));

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->file($filePath);
    }
}
