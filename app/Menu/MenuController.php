<?php

namespace App\Menu;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    public function __invoke(MenuService $service): JsonResponse
    {
        return response()->json([
            'menu' => $service->buildFor(auth()->user()),
        ]);
    }
}
